<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a-card title="Categoria de Películas">
                    <a-button type="primary" slot="extra" @click="showModalCreate">Nuevo</a-button>
                    <table class="table" v-if="!loading">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in data">
                                <th scope="row">{{ d.name }}</th>
                                <td>{{ d.description }}</td>
                                <td>
                                    <span v-if="d.status==1" class="badge badge-pill badge-success">Activado</span>
                                    <span v-if="d.status==2" class="badge badge-pill badge-warning">Desactivado</span>
                                </td>
                                <td>
                                    <a-dropdown>
                                        <a-menu slot="overlay" @click="handleMenuClick">
                                            <a-menu-item key="0" v-if="d.status==2" @click="deactivateCategory(d,1)">Activar</a-menu-item>
                                            <a-menu-item key="1" v-if="d.status==1" @click="deactivateCategory(d,2)">Desactivar</a-menu-item>
                                            <a-menu-item key="2" @click="showModalEdit(d)">Editar</a-menu-item>
                                            <a-menu-item key="3" @click="showDeleteConfirm(d,3)">Eliminar</a-menu-item>
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
        <!-- Modal para Crear -->
        <div class="row">
            <div class="col-12">
                <a-modal title="Crear Nueva categoria" v-model="visible" @ok="hideModalCreate" okText="Guardar" cancelText="Cerrar">
                    <p v-if="!loading_create">
                        <span>Nombre de Categoria</span>
                        <a-input placeholder="Nombre de Categoria" v-model="newName"/>
                    </p>
                    <p v-if="!loading_create">
                        <span>Descripcion</span>
                        <a-textarea
                            v-model="newDescription"
                            placeholder="Descripcion"
                            :autoSize="{ minRows: 3, maxRows: 5 }"
                        />
                    </p>
                    <p v-if="loading_create">
                        <a-spin tip="Creando Caregoria...">
                            <div class="spin-content">
                            </div>
                        </a-spin>
                    </p>
                </a-modal>
            </div>
        </div>
        <!-- Modal para Editar -->
        <div class="row">
            <div class="col-12">
                <a-modal title="Editar categoria" v-model="visible_edit" @ok="hideModalEdit" @cancel="closeModalEdit" okText="Guardar" cancelText="Cerrar">
                    <p v-if="!loading_edit">
                        <span>Nombre de Categoria</span>
                        <a-input placeholder="Nombre de Categoria" v-model="dataEdit.name"/>
                    </p>
                    <p v-if="!loading_edit">
                        <span>Descripcion</span>
                        <a-textarea
                            v-model="dataEdit.description"
                            placeholder="Descripcion"
                            :autoSize="{ minRows: 3, maxRows: 5 }"
                        />
                    </p>
                    <p v-if="loading_edit">
                        <a-spin tip="Actualizando Caregoria...">
                            <div class="spin-content">
                            </div>
                        </a-spin>
                    </p>
                </a-modal>
            </div>
        </div>
    </div>
</template>
<style>
  .spin-content {
    border: 1px solid #91d5ff;
    background-color: #e6f7ff;
    padding: 30px;
  }
</style>
<script>
    export default {
        props:{

        },
        mounted() {
            this.getCategories();
        },
        data(){
            return {
                loading:false,
                loading_create:false,
                loading_edit:false,
                current:1,
                total:0,
                pageSize:0,
                data:[],
                visible: false,
                visible_edit: false,
                newName:'',
                newDescription:'',
                dataEdit:[],
            }
        },
        methods:{
            showDeleteConfirm(obj, status) {
                let me = this;
                me.$confirm({
                    title: 'Seguro que deseas eliminar el registro?',
                    content: 'No se podra recuperar',
                    okText: 'Si',
                    okType: 'danger',
                    cancelText: 'No',
                    onOk() {
                        //console.log('OK');
                        axios.put('/Movies_project/movies_admin/public/category/'+obj.id+'/change-status',{
                            newStatus: status,
                        }).then((response) => {
                            //console.log(response);
                            if (response.data['success']) {
                                me.getCategories();
                                me.$message.success(response.data['success']);
                            } else {
                                me.$message.error(response.data['error']);
                            }
                        });
                    },
                    onCancel() {
                        //console.log('Cancel');
                    },
                });
            },
            showModalEdit(obj) {
                this.dataEdit = [];
                this.dataEdit = obj;
                this.visible_edit = true;
            },
            hideModalEdit(){
                this.loading_edit = true;
                let me = this;
                axios.put('/Movies_project/movies_admin/public/category/'+ me.dataEdit.id +'/update',{
                    data: me.dataEdit
                }).then((response) => {
                    console.log(response)
                    if (response.data['success']) {
                        me.getCategories();
                        me.$message.success(response.data['success']);
                    } else {
                        me.$message.error(response.data['error']);
                    }
                    me.loading_edit = false;
                    me.visible_edit = false;
                }).catch((error) => {
                    console.log(error)
                    me.loading_edit = false;
                    me.visible_edit = false;
                });
            },
            closeModalEdit(){
                this.getCategories();
            },
            showModalCreate() {
                this.visible = true;
            },
            hideModalCreate() {
                this.$message.config({top: `300px`});
                this.loading_create = true;
                let me = this;
                axios.post('/Movies_project/movies_admin/public/category/store',{
                    name: me.newName,
                    description: me.newDescription,
                }).then((response) => {
                    //console.log(response);
                    if (response.data['success']) {
                        me.getCategories();
                        me.$message.success(response.data['success']);
                    } else {
                        me.$message.error(response.data['error']);
                    }
                    me.newName = '';
                    me.newDescription = '';
                    me.loading_create = false;
                    me.visible = false;
                }).catch((error) => {
                    //console.log(error);
                    me.loading_create = false;
                    me.visible = false;
                });
                
            },
            deactivateCategory(obj, status){
                let me = this;
                axios.put('/Movies_project/movies_admin/public/category/'+obj.id+'/change-status',{
                    newStatus: status,
                }).then((response) => {
                    //console.log(response);
                    if (response.data['success']) {
                        me.getCategories();
                        me.$message.success(response.data['success']);
                    } else {
                        me.$message.error(response.data['error']);
                    }
                });
            },
            onChangePage(current) {
                this.current = current;
                this.getCategories();
            },
            handleMenuClick(e) {
                //console.log('click', e);
            },
            getCategories(){
                this.loading = true;
                let me = this;
                axios.get('/Movies_project/movies_admin/public/category/get-data?page='+me.current)
                .then(function(response){
                    //console.log(response);
                    me.data = response.data['data'];
                    me.total = response.data['total']
                    me.pageSize = response.data['per_page']
                    me.loading = false;
                }).catch(function(error){
                    //console.log(error);
                    me.loading = false;
                });
            },
        },
    }
</script>
