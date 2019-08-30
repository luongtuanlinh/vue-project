<template>
    <div>
        <div class="content-header">
            <h1>
                Cài đặt
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Cài đặt</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Giá vé</h3>
                        </div>
                        <div class="box-body">
                            <div class="sc-table">
                                <el-form label-position="left" label-width="40%" :model="options">
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('ticket_price_in_hours') }">
                                        <label class="control-label">Giá vé thường</label>
                                        <money name="ticket_price_in_hours" class="form-control" v-model="options.ticket_price_in_hours"></money>
                                        <span class="help-block" v-if="form.errors.has('ticket_price_in_hours')">{{ form.errors.first('ticket_price_in_hours') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('ticket_price_out_hours') }">
                                        <label class="control-label">Giá vé ngoài giờ hành chính/cuối tuần/ngày lễ</label>
                                        <money name="ticket_price_out_hours" class="form-control" v-model="options.ticket_price_out_hours"></money>
                                        <span class="help-block" v-if="form.errors.has('ticket_price_out_hours')">{{ form.errors.first('ticket_price_out_hours') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <el-button type="success" @click="saveTicketPrice()">Lưu lại</el-button>
                                    </div>
                                </el-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</template>
<script>
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                options: {},
                form: new Form()
            };
        },
        methods: {
            getSettings() {
                axios.get(route('api.setting.get'))
                    .then(res=>{
                       this.options = res.data.data;
                    })
            },
            saveTicketPrice() {
                this.form = new Form(this.options);
                this.form.post(route('api.setting.store'))
                    .then(res=> {
                        if (res.success) {
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
            }
        },
        mounted() {
            this.getSettings();
        }
    }
</script>