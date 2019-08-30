<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa giáo viên
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'teacher.index'}">Danh sách giáo viên</router-link></li>
                <li class="active">Sửa giáo viên</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="teacher"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                    <label class="control-label">Tên giáo viên</label>
                                    <input type="text" name="name" v-model="teacher.name" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('phone') }">
                                    <label class="control-label">Số điện thoại</label>
                                    <input type="text" name="phone" v-model="teacher.phone" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('phone')">{{ form.errors.first('phone') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('email') }">
                                    <label class="control-label">Email</label>
                                    <input type="text" name="email" v-model="teacher.email" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('email')">{{ form.errors.first('email') }}</span>
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
                teacher: {
                    name: '',
                    email: '',
                    phone: '',
                    id: ''
                },
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        methods: {
            getData() {
              axios.get(route('api.teacher.detail', {id: this.id}))
                  .then(res=>{
                      this.teacher = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.teacher);

                this.form.post(route('api.teacher.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'teacher.index' });
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