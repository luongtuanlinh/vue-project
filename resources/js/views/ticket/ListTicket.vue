<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách vé tập
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Danh sách vé tập</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="sc-table">
                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions el-col">
                                        <el-form :inline="true" class="demo-form-inline form-search">
                                            <el-form-item label="Số điện thoại">
                                                <el-input v-model="search.phone"></el-input>
                                            </el-form-item>
                                            <el-form-item label="Tên khách hàng">
                                                <el-input v-model="search.name"></el-input>
                                            </el-form-item>
                                            <el-form-item label="email">
                                                <el-input v-model="search.email"></el-input>
                                            </el-form-item>
                                            <el-form-item label="Ngày">
                                                <el-date-picker
                                                        v-model="search.date"
                                                        type="date"
                                                        format="dd/MM/yyyy"
                                                        value-format="dd/MM/yyyy">
                                                </el-date-picker>
                                            </el-form-item>
                                            <el-form-item>
                                                <el-button type="success" @click="filter" size="small">Tìm kiếm</el-button>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                </div>

                                <el-table
                                        :data="data"
                                        border
                                        stripe
                                        style="width: 100%"
                                        v-loading.body="loading">
                                    <el-table-column type="index" width="30"></el-table-column>
                                    <el-table-column prop="code" label="Mã vé">
                                    </el-table-column>
                                    <el-table-column prop="start_time" label="Từ">
                                        <template slot-scope="scope">
                                            {{ scope.row.start_time | formatDateTime }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="end_time" label="Đến">
                                        <template slot-scope="scope">
                                            {{ scope.row.end_time | formatDateTime }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="item" label="Xe">
                                    </el-table-column>
                                    <el-table-column prop="price_type" label="Loại hình">
                                    </el-table-column>
                                    <!--<el-table-column prop="type" label="Nơi tập">-->
                                        <!--<template slot-scope="scope">-->
                                            <!--{{ scope.row.type | typeTicket }}-->
                                        <!--</template>-->
                                    <!--</el-table-column>-->
                                    <el-table-column prop="name" label="Tên khách hàng">
                                    </el-table-column>
                                    <el-table-column prop="phone" label="Số điện thoại">
                                    </el-table-column>
                                    <el-table-column prop="email" label="Email">
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center" width="100">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="default" size="small">In vé</el-button>
                                            </el-button-group>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <div class="pagination-wrap">
                                    <el-pagination
                                            v-if="total>0"
                                            @size-change="handleSizeChange"
                                            @current-change="handleCurrentChange"
                                            :current-page.sync="meta.page"
                                            :page-sizes="[10, 20, 50, 100]"
                                            :page-size="meta.per_page"
                                            layout="total, sizes, prev, pager, next"
                                            :total="total">
                                    </el-pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                courses: [],
                search: {},
                loading: false,
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.ticket.list', _.merge(this.meta, this.search)))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            filter() {
                this.getData()
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'student.info', params: { id: scope.row.id } });
            },
            handleSizeChange(event) {
                this.meta.per_page = event;
                this.getData();
            },
            handleCurrentChange(event) {
                this.meta.page = event;
                this.getData();
            }
        },
        mounted() {
            this.getData();
        }

    }
</script>
