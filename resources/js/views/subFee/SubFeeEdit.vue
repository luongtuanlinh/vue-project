<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa lệ phí
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'subfee.index'}">Danh sách lệ phí</router-link></li>
                <li class="active">Sửa lệ phí</li>
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
                                <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                    <label class="control-label">Tên lệ phí <span class="required">*</span></label>
                                    <input type="text" name="name" v-model="data.name" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('fee') }">
                                    <label class="control-label">Lệ phí <span class="required">*</span></label>
                                    <money name="fee" class="form-control" v-model="data.fee"></money>
                                    <span class="help-block" v-if="form.errors.has('fee')">{{ form.errors.first('fee') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('description') }">
                                    <label class="control-label">Ghi chú</label>
                                    <textarea name="note" v-model="data.description" class="form-control"></textarea>
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
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                data: {
                    name: '',
                    fee: '',
                    description: ''
                },
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        watch: {
            'data.fee': function (val) {
                this.form.errors.clear('fee')
            }
        },
        methods: {
            getData() {
              axios.get(route('api.subfee.detail', {id: this.id}))
                  .then(res=>{
                      this.data = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.data);

                this.form.post(route('api.subfee.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'subfee.index' });
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