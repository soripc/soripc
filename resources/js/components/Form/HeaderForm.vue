<template>
    <div class="row">
        <div class="col-sm-2 text-center mt-3 mb-0">
            <logo url="/"
                  :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''"></logo>
        </div>
        <div class="col-sm-6 text-left mt-3 mb-0">
            <address class="ib mr-2">
                <span class="font-weight-bold">{{ company.name }}</span>
                <template v-if="establishment.address !== ''">
                    <br>
                    <span>{{ establishment.address }}</span>
                </template>
                <template v-if="establishment.email !== ''">
                    <br>
                    <span>{{ establishment.email }}</span>
                </template>
                <template v-if="establishment.telephone !== ''">
                    <br>
                    <span>{{ establishment.telephone }}</span>
                </template>
            </address>
        </div>
    </div>
</template>

<script>

import Logo from '../../views/tenant/companies/logo.vue'

export default {
    name: 'HeaderForm',
    components: {Logo},
    props: {
        'company': {
            required: true,
        },
        'establishment': {
            required: true,
        }
    },
    data() {
        return {}
    },
}
</script>
