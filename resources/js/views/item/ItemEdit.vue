<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa xe
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'item.index'}">Danh sách xe</router-link></li>
                <li>Sửa xe</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="item"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="form-group" :class="{ 'has-error': form.errors.has('type') }">
                                    <label class="control-label">Hạng xe</label>
                                    <select2 name="type" v-model="item.type" :options="types" @change="form.errors.clear('type')"/>
                                    <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('type') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                    <label class="control-label">Tên xe</label>
                                    <input type="text" name="name" v-model="item.name" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <el-form-item>
                                    <el-button type="success" @click="onSubmit()" :loading="loading">
                                        Submit
                                    </el-button>
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                </div>
            </el-form>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                item: {
                    name: '',
                    type: '',
                },
                types: [],
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        methods: {
            getData() {
              axios.get(route('api.item.detail', {id: this.id}))
                  .then(res=>{
                      this.item = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.item.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'item.index' });
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                        } else {
                            this.$notify.error({
                                title: 'Error',
                                message: res.msg
                            });
                        }
                    })
                    .catch(error=> {
                        this.loading = false;
                        console.log(error);
                    })
            },
            getAllType() {
                axios.get(route('api.item.type.all'))
                    .then(res=>{
                        this.types = res.data.data;
                    })
            },
            keydown(event){
                this.form.errors.clear(event.target.name);
            }
        },
        mounted() {
            this.getData();
            this.getAllType();
        }

    }
</script>