<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa giá
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'item.type.index'}">loại xe</router-link></li>
                <li class="active">Sửa giá</li>
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
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('type') }">
                                        <label class="control-label">Hạng xe</label>
                                        <select2 name="item_type_id" v-model="data.item_type_id" :options="types" @change="changeItemType"/>
                                        <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('item_type_id') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('type') }">
                                        <label class="control-label">Loại hình thuê</label>
                                        <select2 name="price_type" v-model="data.price_type" :options="price_types" @change="changeItemType"/>
                                        <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('price_type') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('type') }">
                                        <label class="control-label">Thời gian thuê</label>
                                        <select2 name="time_type" v-model="data.time_type" :options="time_types" @change="changeItemType"/>
                                        <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('time_type') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('price') }">
                                        <label class="control-label">Giá</label>
                                        <money name="price" class="form-control" v-model="data.price"></money>
                                        <span class="help-block" v-if="form.errors.has('price')">{{ form.errors.first('price') }}</span>
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
    import func from '@/utils/functions';

    export default {
        data() {
            return {
                data: {
                    item_type_id: '',
                    price_type: '',
                    time_type: '',
                    price: '',
                    id: ''
                },
                objetTypePrice : func.objectForTypePrice(),
                types: [],
                price_types : [],
                time_types : [],
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        methods: {
            getData() {
              axios.get(route('api.item.type.price.detail', {id: this.id}))
                  .then(res=>{
                      this.data = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.data);

                this.form.post(route('api.item.type.price.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'item.type.price.index' });
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
            },
            changeItemType(){
                this.form.errors.clear('item_type_id');
                let item_type_id = this.types.find(x => x.id === parseInt(this.data.item_type_id)).type;
                let object = this.objetTypePrice.find(x => x.id === item_type_id);
                this.price_types = object.price_types;
                this.time_types = object.time_types;
            }
        },
        mounted() {
            this.getAllType();
            this.getData();
        },
        updated(){
            if(this.data.item_type_id != ''){
                let item_type_id = this.types.find(x => x.id === parseInt(this.data.item_type_id)).type;
                let object = this.objetTypePrice.find(x => x.id === item_type_id);
                this.price_types = object.price_types;
                this.time_types = object.time_types;
                console.log(object.price_types);
            }
        }

    }
</script>