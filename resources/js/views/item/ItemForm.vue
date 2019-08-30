<template>
    <div>
        <div class="content-header">
            <h1>
                Thêm xe
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'item.index'}">Danh sách xe</router-link></li>
                <li class="active">Thêm xe</li>
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
                                    <label class="control-label">Loại xe</label>
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
    import Form from 'form-backend-validation';

    export default {
        created () {
            this.select2Settings = this.getSelect2Settings({
                url: '/api/select-roles',
                field_name: 'title',
                placeholder: 'Hãy chọn role ...',
                term_name: 'title'
            });
        },
        data() {
            return {
                item: {
                    name: '',
                    type: null
                },
                types: [],
                form: new Form(),
                loading: false,
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.item.store'))
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
                        this.$notify.error({
                            title: 'Error',
                            message: 'đã có lỗi xảy ra, vui lòng thử lai.'
                        });
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
            },
            getSelect2Settings(options = {}) {
                return {
                    ajax: {
                        headers: {
                            Authorization: 'Bearer ' + window.token
                        },
                        cache: true,
                        delay: 200,
                        url: options.url,
                        data: function (params) {
                            return Object.assign({
                                [options.term_name || options.field_name]: params.term,
                                page: params.page
                            }, options.params || {});
                        },
                        processResults: function processResults(data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.data,
                                pagination: {
                                    more: params.page * data.per_page < data.total
                                }
                            };
                        },
                    },
                    allowClear: true,
                    multiple: options.multiple || false,
                    placeholder: options.placeholder,
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;

                        return repo[options.field_name];
                    },
                    templateSelection: function (repo) {
                        if (repo.selected) return repo.text;

                        if (repo.id) {
                            var textShow = repo[options.field_name];
                        } else {
                            var textShow = repo.text;
                        }

                        return textShow;
                    },
                    escapeMarkup: function escapeMarkup(markup) {
                        return markup;
                    },
                };
            },
        },
        mounted() {
            this.getAllType();
        }
    }
</script>
