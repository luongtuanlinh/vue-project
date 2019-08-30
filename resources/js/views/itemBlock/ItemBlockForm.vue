<template>
    <div>
        <div class="content-header">
            <h1>
                Thêm lịch khóa xe
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'item.block.index'}">Lịch khóa xe</router-link></li>
                <li class="active">Thêm lịch khóa xe</li>
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
                                    <select2 name="type" v-model="item.type" :options="types" @change="selectType"/>
                                    <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('type') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('item_id') }">
                                    <label class="control-label">Tên xe</label>
                                    <select2 name="type" v-model="item.item_id" :options="items" :settings="{multiple: true}" @change="form.errors.clear('item_id')"/>
                                    <span class="help-block" v-if="form.errors.has('item_id')">{{ form.errors.first('item_id') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('date') }">
                                    <label class="control-label">Ngày khóa</label>
                                    <el-date-picker
                                            v-model="item.date"
                                            type="date"
                                            format="dd/MM/yyyy"
                                            @change="form.errors.clear('date')"
                                            value-format="dd/MM/yyyy"
                                            placeholder="">
                                    </el-date-picker>
                                    <span class="help-block" v-if="form.errors.has('date')">{{ form.errors.first('date') }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Thời gian</label>
                                    <div>
                                        <el-time-select
                                                placeholder="Từ"
                                                :class="{ 'has-error': form.errors.has('start_time') }"
                                                v-model="item.start_time"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('start_time')"
                                                value-format="HH:mm:ss"
                                                :picker-options="{
                                                  start: '07:30',
                                                  step: '01:00',
                                                  end: '17:30'
                                                }">
                                        </el-time-select>
                                        <el-time-select
                                                placeholder="Đến"
                                                :class="{ 'has-error': form.errors.has('end_time') }"
                                                v-model="item.end_time"
                                                @change="form.errors.clear('end_time')"
                                                format="dd/MM/yyyy"
                                                value-format="HH:mm:ss"
                                                :picker-options="{
                                                  start: '07:30',
                                                  step: '01:00',
                                                  end: '17:30',
                                                  minTime: item.start_time
                                                }">
                                        </el-time-select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Ghi chú</label>
                                    <input type="text" name="note" v-model="item.note" class="form-control" @keydown="keydown">
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
    import _ from 'lodash';

    export default {
        data() {
            return {
                item: {},
                types: [],
                items: [],
                allItem: [],
                form: new Form(),
                loading: false,
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.item.block.store'))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'item.block.index' });
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
            getData() {
                axios.get(route('api.item.type.all'))
                    .then(res=>{
                        this.types = res.data.data;
                    })
                axios.get(route('api.item.all'))
                    .then(res=>{
                        this.allItem = res.data;
                        this.items = res.data;
                    })
            },
            keydown(event){
                this.form.errors.clear(event.target.name);
            },
            selectType(type) {
                this.form.errors.clear('type');
                this.item.item_id = null;
                this.items = _.filter(this.allItem, function (o) {
                    return o.type==type;
                })
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
