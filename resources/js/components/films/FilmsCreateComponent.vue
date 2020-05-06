<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a-card title="Nueva Peliculas" v-if="!loading_create">
                    <p><span style="color:red">*</span>Imagen de la pelicula</p>
                    <a-upload
                        action="/Movies_project/movies_admin/public/picture/upload"
                        listType="picture"
                        accept = "image/*"
                        :headers = "headers"
                        :data = "setDataUploadPhoto"
                        :beforeUpload="beforeUpload"
                        :multiple = "false"
                        :remove="handleRemove"
                        :fileList = "fileList"
                        @change="handleChange"
                    >
                        <a-button 
                            v-if="Array.isArray(dataFilm.picture)"
                            :disabled="!Array.isArray(dataFilm.picture)"
                        > <a-icon type="upload" /> Subir imagen </a-button>
                    </a-upload>
                    <br>
                    <p>
                        <span><span style="color:red">*</span>Nombre:</span>
                        <a-input placeholder="Nombre de la Pelicula" v-model="dataFilm.name"/>
                    </p>
                    <p>
                        <span>Descripcion(Opcional):</span>
                        <a-textarea
                            v-model="dataFilm.description"
                            placeholder="Descripcion de la Pelicula"
                            autoSize
                        />
                    </p>
                    <p>
                        <span>Fecha de Estreno(Opcional):</span>
                        <br>
                        <a-date-picker @change="onChangeReleaseDate" style="width:100%"/>
                    </p>
                    <p>
                        <span>Director(Opcional):</span>
                        <a-input placeholder="Director de la Pelicula" v-model="dataFilm.director"/>
                    </p>
                    <p>
                        <span>Categorias:</span>
                        <a-select
                            mode="multiple"
                            style="width: 100%"
                            @change="handleChangeCategory"
                            placeholder="Please select"
                        >
                            <a-select-option v-for="c in categories" :key="c.id+'|'+c.name">{{ c.name }}</a-select-option>
                        </a-select>
                    </p>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary" @click="saveFilm">Agregar Pelicula</button>
                        </div>
                    </div>
                </a-card>
                <div class="row" v-if="loading_create">
                    <div class="col-12">
                        <p>
                            <a-spin tip="Creando Pelicula...">
                                <div class="spin-content">
                                </div>
                            </a-spin>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
  /* tile uploaded pictures */
  .upload-list-inline >>> .ant-upload-list-item {
    float: left;
    width: 200px;
    margin-right: 8px;
  }
  .upload-list-inline >>> .ant-upload-animate-enter {
    animation-name: uploadAnimateInlineIn;
  }
  .upload-list-inline >>> .ant-upload-animate-leave {
    animation-name: uploadAnimateInlineOut;
  }
</style>
<script>
    export default {
        props: {
            categories:Array,
        },
        data() {
            return {
                loading_create:false,
                fileList:[],
                folderFilm:'films',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataFilm:{
                    name:'',
                    description:'',
                    release_date:'',
                    director:'',
                    picture:[],
                    categories_film:[],
                }
            }
        },
        mounted() {
            
        },
        methods: {
            saveFilm(){
                this.$message.config({top: `300px`});
                this.loading_create = true;
                let me = this;
                if (Array.isArray(me.dataFilm.picture) || me.dataFilm.name == "") {
                    //si es array es por que no se ha seleccionado imagen
                    me.$message.error('Favor de llenar los campos requeridos con el asterisco (*)');
                } else {
                    axios.post('/Movies_project/movies_admin/public/films/store',{
                        data: me.dataFilm
                    }).then((response) => {
                        //console.log(response)
                        if (response.data['success']) {
                            me.fileList = [];
                            me.dataFilm = {
                                name:'',
                                description:'',
                                release_date:'',
                                director:'',
                                picture:[],
                                categories_film:[],
                            };
                            me.$message.success(response.data['success']);
                        } else {
                            me.$message.error(response.data['error']);
                        }
                        me.loading_create = false;
                    }).catch((error) => {
                        //console.log(error)
                        me.loading_create = false;
                    });
                }
                
            },
            handleChangeCategory(value) {
                console.log(`selected ${value}`);
                this.dataFilm.categories_film = value;
            },
            onChangeReleaseDate(date, dateString) {
                console.log(date, dateString);
                this.dataFilm.release_date = dateString;
            },
            handleChange(info) {
                let fileList = [...info.fileList];

                // 1. Limit the number of uploaded files
                //    Only to show two recent uploaded files, and old ones will be replaced by the new
                fileList = fileList.slice(-2);

                // 2. read from response and show file link
                fileList = fileList.map(file => {
                    if (file.response) {
                        // Component will show file.url as link
                        file.url = file.response.url;
                    }
                    return file;
                });

                this.fileList = fileList;

                this.$message.config({top: `300px`});
                if (info.file.status !== 'uploading') {
                    //console.log(info.file, info.fileList);
                    console.log(info.file);
                    this.dataFilm.picture = info.file.response;
                }
                if (info.file.status === 'done') {
                    this.$message.success(`${info.file.name} se subio exitosamente.`);
                } else if (info.file.status === 'error') {
                    this.$message.error(`${info.file.name} no se pudo subir.`);
                }
            },
            beforeUpload(file) {
                this.$message.config({top: `300px`});
                const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
                if (!isJpgOrPng) {
                    this.$message.error('El archivo no es una imagen!');
                }
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                    this.$message.error('La imagen debe ser menor que 2MB!');
                }
                return isJpgOrPng && isLt2M;
            },
            handleRemove(file){
                let me = this;
                axios.post('/Movies_project/movies_admin/public/picture/remove',{
                    slug : file.uid,
                    folder : me.folderFilm
                }).then((response) => {
                    //console.log(response);
                    me.dataFilm.picture = [];
                }).catch((error) => {
                    //console.log(error);
                });
            },
            setDataUploadPhoto(file){ // Los datos que se veran en el $request
                return { dataFile : file.uid, nameFile: file.name, size: file.size , picture_section:2 , folder:this.folderFilm, heightPicture:500, widthPicture:500};
            },
        }
    }
</script>
