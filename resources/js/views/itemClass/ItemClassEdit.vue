<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa hạng xe
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'item.class.index'}">Hạng xe</router-link></li>
                <li class="active">Sửa hạng xe</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="data"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="box-body">
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                        <label class="control-label">Tên hạng xe</label>
                                        <input type="text" name="name" v-model="data.name" class="form-control" @keydown="keydown">
                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('note') }">
                                        <label class="control-label">Mô tả</label>
                                        <textarea name="note" v-model="data.note" class="form-control"></textarea>
                                    </div>
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
                data: {
                    name: '',
                    note: '',
                    id: ''
                },
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        methods: {
            getData() {
              axios.get(route('api.item.class.detail', {id: this.id}))
                  .then(res=>{
                      this.data = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.data);

                this.form.post(route('api.item.class.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'item.class.index' });
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
            keydown(event){
                this.form.errors.clear(event.target.name);
            }
        },
        mounted() {
            this.getData();
        }

    }
</script>