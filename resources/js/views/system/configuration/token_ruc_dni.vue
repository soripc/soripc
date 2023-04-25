<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Token consulta RUC/DNI</h3>
        </div>
        <div class="card-body">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.token_api}">
                            <label class="control-label">Tu token</label>
                            <template v-if="action !== 'update'">
                                <el-input v-model="form.token_api_text"
                                          disabled>
                                    <el-button type="primary" slot="append" @click.prevent="action = 'update'">Editar
                                    </el-button>
                                </el-input>
                            </template>
                            <template v-else>
                                <el-input v-model="form.token_api">
                                    <el-button slot="append" @click.prevent="action = ''">Cancelar</el-button>
                                </el-input>
                                <small class="form-control-feedback"
                                       v-if="errors.token_api"
                                       v-text="errors.token_api[0]"></small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4" v-if="action ==='update'">
                <el-button type="primary" @click.prevent="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            loading_submit: false,
            // headers: headers_token,
            resource: "configurations",
            errors: {},
            form: {},
            action: ''
        };
    },
    async created() {
        await this.initForm();
        await this.getRecord();
    },
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                url_apiruc: null,
                token_api_text: null,
                token_api: null,
                token_false: false,
                token_apiruc_show: '',
            };
            this.action= '';
        },
        async getRecord() {
            await this.$http.get(`/${this.resource}/get_api_ruc_dni`)
                .then(response => {
                    this.form = response.data;
                });
        },
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/store_api_ruc_dni`, this.form)
                .then(response => {
                    this.initForm();
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                    this.getRecord();
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
    }
};
</script>

