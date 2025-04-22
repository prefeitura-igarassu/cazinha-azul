<template>

<Page>

	<TabView :lazy="true">
		<TabPanel header="Usuários">
			<UsuarioTable />
		</TabPanel>
		
		<TabPanel header="Grupos">
			<GrupoTable />
		</TabPanel>

		<TabPanel header="Log">
			<LogTable />
		</TabPanel>
	</TabView>

</Page>

</template>

<script>
import Page from "@/components/Page.vue";
import UsuarioTable from './UsuarioTable.vue';
import GrupoTable from "@/pages/usuarios_grupos/GrupoTable.vue";
import LogTable from "@/pages/usuarios_logs/LogTable.vue";

import TabPanel from "primevue/tabpanel";
import TabView from "primevue/tabview";

export default {
    components: { Page , UsuarioTable , GrupoTable , LogTable, TabView, TabPanel },

    data() {
        return {

        };
    },
}
</script>

<style>

.p-tabview .p-tabview-panels {
	padding-left: 0;
	padding-right: 0;
}

</style>