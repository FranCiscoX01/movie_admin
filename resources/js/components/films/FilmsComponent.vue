<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a-card title="Peliculas">
                    <a-button href="/Movies_project/movies_admin/public/films/create" type="primary" slot="extra">Nueva</a-button>
                    <a-button @click="showModalExcel" type="primary" slot="extra">Cargar Excel</a-button>
                    <table class="table" v-if="!loading">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Imagen</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Description</th>
                                <th scope="col">Fecha de Estreno</th>
                                <th scope="col">Director</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in data">
                                <th>
                                    <a-popover :title="d.name" placement="right">
                                        <template slot="content">
                                            <img :src="'uploads/films/'+ d.url" height="190" width="130">
                                        </template>
                                        <a-avatar shape="square" :size="96" icon="user" :src="'uploads/films/'+ d.url"/>
                                    </a-popover>
                                </th>
                                <td>{{ d.name }}</td>
                                <td>{{ d.description }}</td>
                                <td>{{ dateString(d.release_date) }}</td>
                                <td>{{ d.director }}</td>
                                <td>
                                    <span v-if="d.status==1" class="badge badge-success">Activo</span>
                                    <span v-if="d.status==2" class="badge badge-warning">Desactivado</span>
                                </td>
                                <td>
                                    <a-dropdown>
                                        <a-menu slot="overlay" @click="handleMenuClick">
                                            <a-menu-item key="0" v-if="d.status==2" @click="changeStatus(d,1)">Activar</a-menu-item>
                                            <a-menu-item key="1" v-if="d.status==1" @click="changeStatus(d,2)">Desactivar</a-menu-item>
                                            <a-menu-item key="2" @click="showModalEdit(d)">Editar</a-menu-item>
                                            <a-menu-item key="3" @click="changeStatus(d,3)">Eliminar</a-menu-item>
                                        </a-menu>
                                        <a-button> Acciones <a-icon type="down" /> </a-button>
                                    </a-dropdown>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a-pagination v-model="current" :total="total" @change="onChangePage" :pageSize="pageSize" />
                    <div class="row">
                        <div class="col-12 text-center">
                            <a-spin v-if="loading">
                                <a-icon slot="indicator" type="loading" style="font-size: 54px" spin />
                            </a-spin>
                        </div>
                    </div>
                </a-card>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a-modal
                    title="Editar Pelicula"
                    :visible="visible_edit"
                    @ok="handleOkEdit"
                    :confirmLoading="confirmLoading_edit"
                    @cancel="handleCancelEdit"
                >
                    <div class="row">
                        <div class="col-12" v-if="loading_edit">
                            <p>
                                <a-spin tip="Cargando Pelicula...">
                                    <div class="spin-content">
                                    </div>
                                </a-spin>
                            </p>
                        </div>
                        <div class="col-12" v-if="!loading_edit">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a-avatar shape="square" :size="164" icon="user" :src="'uploads/films/'+ picture_edit"/>
                                </div>
                            </div>
                            <p>
                                <span>Nombre:</span>
                                <a-input placeholder="Nombre de la pelicula" v-model="dataEdit.name"/>
                            </p>
                            <p>
                                <span>Descripcion:</span>
                                <a-textarea placeholder="Descripcion de la pelicula" autoSize v-model="dataEdit.description"/>
                            </p>
                            <p>
                                <span>Fecha de Estreno:</span>
                                <br>
                                <a-date-picker @change="onChangeEdit" :defaultValue="moment(dataEdit.release_date, dateFormat)" style="width:100%"/>
                            </p>
                            <p>
                                <span>Director:</span>
                                <a-input placeholder="Nombre de la pelicula" v-model="dataEdit.director"/>
                            </p>
                            <p>
                                <span>Cateorias:</span>
                                <a-select
                                    mode="multiple"
                                    style="width: 100%"
                                    @change="handleChangeCategory"
                                    placeholder="Please select"
                                    v-model="category_selected"
                                >
                                    <a-select-option v-for="c in categories" :key="c.id+'|'+c.name">{{ c.name }}</a-select-option>
                                </a-select>
                            </p>
                            <p>
                                <span>Nueva Imagen</span>
                                <a-upload
                                    action="/Movies_project/movies_admin/public/picture/upload"
                                    listType="picture"
                                    accept = "image/*"
                                    :headers = "headers"
                                    :data = "setDataUploadNewPhoto"
                                    :beforeUpload="beforeUploadNewImg"
                                    :multiple = "false"
                                    :remove="handleRemoveNewImg"
                                    :fileList = "fileListNewImg"
                                    @change="handleChangeNewImg"
                                >
                                    <a-button 
                                        v-if="Array.isArray(new_picture)"
                                        :disabled="!Array.isArray(new_picture)"
                                    > <a-icon type="upload" /> Subir imagen </a-button>
                                </a-upload>
                            </p>
                        </div>
                    </div>
                </a-modal>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                    <a-modal title="Basic Modal" v-model="visible_excel" @ok="handleOkExcel">
                    <a-upload-dragger
                        v-if="!load_excel"
                        name="file"
                        :multiple = "false"
                        action="/Movies_project/movies_admin/public/film/upload/excel"
                        :headers = "headers"
                        :data = "setDataUploadPhoto"
                        :fileList = "fileList"
                        accept = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        :remove="handleRemoveExcel"
                        @change="handleChangeExcel"
                    >
                        <p class="ant-upload-drag-icon" >
                            <a-icon type="inbox" />
                        </p>
                        <p class="ant-upload-text">Click or drag file to this area to upload</p>
                        <p class="ant-upload-hint">
                            Support for a single or bulk upload. Strictly prohibit from uploading company data or other
                            band files
                        </p>
                        
                    </a-upload-dragger>
                    <div class="row" v-if="load_excel">
                        <div class="col-12 text-center">
                            <a-spin>
                                <a-icon slot="indicator" type="loading" style="font-size: 54px" spin />
                            </a-spin>
                        </div>
                    </div>
                </a-modal>
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
function getBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}
import moment from 'moment';
    export default {
        props: {
            categories:Array,
        },
        data() {
            return {
                dateFormat: 'YYYY/MM/DD',
                folderFilm:'films',
                loading:false,
                loading_edit:false,
                data:[],
                current:1,
                total:0,
                pageSize:0,
                visible_edit: false,
                confirmLoading_edit: false,
                dataEdit:[],
                category_selected:[],
                picture_edit:'',
                visible_excel:false,
                fileList:[],
                fileListNewImg:[],
                previewVisibleNew: false,
                uploadingNewImg: false,
                previewImageNew: '',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                load_excel:false,
                new_picture:[],
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            handleCancelNewImg() {
                this.previewVisibleNew = false;
            },
            handleChangeNewImg(info) {
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

                this.fileListNewImg = fileList;

                this.$message.config({top: `300px`});
                if (info.file.status !== 'uploading') {
                    //console.log(info.file, info.fileList);
                    console.log(info.file);
                    this.new_picture = info.file.response;
                }
                if (info.file.status === 'done') {
                    this.$message.success(`${info.file.name} se subio exitosamente.`);
                } else if (info.file.status === 'error') {
                    this.$message.error(`${info.file.name} no se pudo subir.`);
                }
            },
            handleRemoveNewImg(file) {
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
            beforeUploadNewImg(file) {
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
            showModalExcel(){
                this.visible_excel = true;
            },
            handleOkExcel(e) {
                this.load_excel = true;
                let me = this;
                axios.get('/Movies_project/movies_admin/public/films/upload-data-excel')
                .then((response) => {
                    console.log(response)
                    me.load_excel = true;
                    me.visible_excel = false;
                    me.$message.success("Se hizo el registro exitosamente");
                    me.getData();
                }).catch((error) => {
                    console.log(error)
                    me.load_excel = true;
                    me.visible_excel = false;
                    me.$message.error("Ocurrio un error en agregar registros");
                    me.getData();
                });
            },
            handleRemoveExcel(file){
                axios.post('/Movies_project/movies_admin/public/film/remove/excel',{
                    data : file,
                }).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                });
            },
            handleChangeExcel(info) {
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



                const status = info.file.status;
                if (status !== 'uploading') {
                    console.log(info.file, info.fileList);
                }
                if (status === 'done') {
                    this.$message.success(`${info.file.name} file uploaded successfully.`);
                } else if (status === 'error') {
                    this.$message.error(`${info.file.name} file upload failed.`);
                }
            },
            changeStatus(obj, status){
                let me = this;
                axios.put('/Movies_project/movies_admin/public/films/'+obj.id+'/change-status',{
                    newStatus: status,
                }).then((response) => {
                    //console.log(response);
                    if (response.data['success']) {
                        me.getData();
                        me.$message.success(response.data['success']);
                    } else {
                        me.$message.error(response.data['error']);
                    }
                });
            },
            showModalEdit(obj) {
                this.loading_edit = true;
                this.visible_edit = true;
                this.picture_edit = '';
                this.category_selected = [];
                this.dataEdit = [];
                this.dataEdit = obj;
                let me = this;
                axios.get('/Movies_project/movies_admin/public/films/'+ obj.id +'/get-all-info')
                .then((response) => {
                    //console.log(response)
                    response.data.categories.forEach(function(elem){
                        me.category_selected.push(elem.category_id+'|'+elem.name);
                    });
                    me.picture_edit = response.data.picture_url.url
                    me.loading_edit = false;
                }).catch((error) => {
                    //console.log(error)
                });
            },
            handleOkEdit(e) {
                this.confirmLoading_edit = true;
                let me = this;
                if (me.dataEdit.name != '') {
                    axios.post('/Movies_project/movies_admin/public/films/update',{
                        data: me.dataEdit,
                        categories: me.category_selected,
                        newImage: me.new_picture,
                    }).then((response) => {
                        console.log(response)
                        if (response.data['success']) {
                            me.uploadingNewImg = false;
                            me.fileListNewImg = [];
                            me.new_picture = [];
                            me.getData();
                            me.$message.success(response.data['success']);
                        } else {
                            me.$message.error(response.data['error']);
                        }
                        me.visible_edit = false;
                        me.confirmLoading_edit = false;
                    }).catch((error) => {
                        //console.log(error)
                        me.$message.error('Ocurrio un error');
                    });
                } else {
                    me.$message.error('El nombre no puede estar vacio');
                    me.confirmLoading_edit = false;
                }
            },
            handleCancelEdit(e) {
                this.fileListNewImg = [];
                this.getData();
                this.visible_edit = false;
            },
            onChangeEdit(date, dateString) {
                //console.log(date, dateString);
                this.dataEdit.release_date = dateString;
            },
            handleChangeCategory(value) {
                //console.log(`selected ${value}`);
            },
            handleMenuClick(e) {
                //console.log('click', e);
            },
            setDataUploadPhoto(file){ // Los datos que se veran en el $request
                return { dataFile : file.uid, nameFile: file.name, size: file.size };
            },
            setDataUploadNewPhoto(file){ // Los datos que se veran en el $request
                return { dataFile : file.uid, nameFile: file.name, size: file.size , picture_section:2 , folder:this.folderFilm, heightPicture:500, widthPicture:500};
            },
            onChangePage(current) {
                this.current = current;
                this.getData();
            },
            dateString(release_date){
                if (release_date != null) {
                    var new_date = release_date.replace('-','/')
                    var fecha = new Date(new_date);
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };

                    return fecha.toLocaleDateString("es-MX", options)
                }
            },
            getData(){
                this.loading = true;
                let me = this;
                axios.get('/Movies_project/movies_admin/public/films/get-data?page='+me.current)
                .then((response) => {
                    console.log(response)
                    me.data = response.data['data'];
                    me.total = response.data['total']
                    me.pageSize = response.data['per_page']
                    me.loading = false;
                }).catch((error) => {
                    //console.log(error)
                    me.loading = false;
                });
            },
            moment,
        }
    }
</script>
