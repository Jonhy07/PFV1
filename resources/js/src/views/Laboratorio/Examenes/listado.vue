<template>
	<div>
		<vx-card>
			<div class = "demo-alignment">
				<h2>Examenes</h2>
				<!-- <vx-tooltip text = "Agregar nuevo registro"> <vs-button radius type = "gradient" icon-pack = "feather" icon = "icon-plus" @click="aNuevo" color = "primary" size = "large" ></vs-button>  </vx-tooltip> -->
			</div>
				<vs-divider position="right">PID&#174;</vs-divider>
			<vs-prompt title="Exportar a Excel" class="export-options" @cancle="clearFields" @accept="exportToExcel" accept-text="Exportar" cancel-text="Cancelar" @close="clearFields" :active.sync="activePrompt">
				<vs-input v-model="fileName" placeholder="Nombre de archivo" class="w-full" />
				<v-select v-model="selectedFormat" :options="formats" class="my-4" />
				<div class="flex">
					<span class="mr-4">Ancho automatico de celda:</span>
					<vs-switch v-model="cellAutoWidth">Cell Auto Width</vs-switch>
				</div>
			</vs-prompt>
			<vs-table stripe pagination max-items="7" search :data="arrayData" noDataText="No hay datos disponibles" >
				<template slot="header">
					<vs-button @click="activePrompt=true">Exportar</vs-button>
				</template>
				<template slot="thead">
					<vs-th>Paciente</vs-th>
					<vs-th>Tipo de examen</vs-th>
					<vs-th>Fecha</vs-th>
					<vs-th>Estado</vs-th>
					<vs-th>Acciones</vs-th>
				</template>

				<template slot-scope="{ data }">
					<vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
						<vs-td>{{ data[indextr].datos_paciente[0].nombres + ' ' + data[indextr].datos_paciente[0].apellidos }}</vs-td>
						<vs-td>{{ data[indextr].tipo_examen[0].nombre }}</vs-td>
						<vs-td>{{ data[indextr].examen.fecha_examen }}</vs-td>
						<vs-td :data="data[indextr].estado">
							 <vs-chip :color="data[indextr].estado == 0 ? 'primary' : data[indextr].estado == 1 ? 'success':'success'">
                  <span>{{data[indextr].estado == 0 ? 'Programado' : data[indextr].estado == 1 ? 'Realizado':'L'}}</span>
                                    </vs-chip>
						</vs-td>
						<vs-td>
							<div class="flex items-center">
								<!-- <vx-tooltip text="Responder"><vs-button @click="$router.push('/ingresar/examen/'+data[indextr].id)"  radius color="dark" type="flat" icon="dehaze" size="large"></vs-button></vx-tooltip> -->
								<vx-tooltip text="Responder"><vs-button @click="$router.push('/ingresar/examen/'+data[indextr].id)" v-if="data[indextr].estado == 0" radius color="dark" type="flat" icon="vertical_split" size="large"></vs-button></vx-tooltip>
							</div>
						</vs-td>
					</vs-tr>
				</template>
			</vs-table>
		</vx-card>
	</div>
</template>

   
<script>
import VueApexCharts from "vue-apexcharts";
import StatisticsCardLine from "@/components/statistics-cards/StatisticsCardLine.vue";
//import analyticsData from './ui-elements/card/analyticsData.js'
import ChangeTimeDurationDropdown from "@/components/ChangeTimeDurationDropdown.vue";
import VxTimeline from "@/components/timeline/VxTimeline";
import vSelect from 'vue-select'
import axios from "axios";
export default {
  data() {
		return {
            arrayData: [],
            arrayDatos: [],
			fileName: '',
			formats:['xlsx', 'csv', 'txt'],
			cellAutoWidth: true,
			selectedFormat: 'xlsx',
			headerVal: ['id', 'nombress', 'apellidoss', 'tipo_examens', 'resultado', 'estado' ],
			headerTitle: ['Id', 'Nombres del Paciente', 'Apellidos del Paciente', 'Tipo de examen', 'Fecha', 'Estado'],
			activePrompt: false,
            nombre: "",
            nombreP: "",
			apellidoP: "",
            codigoE: "",
			switch2: false,
			id: 0,
			estado: null
		};
	},
	components: {
		VueApexCharts,
		StatisticsCardLine,
		ChangeTimeDurationDropdown,
		VxTimeline,
		vSelect,
	},
	methods: {
        aNuevo () {
		 this.$router.push('/ingresar/examen')
		},
			traerDatos(tabla){
				tabla.forEach(function(valor, indice, array){
					valor.nombress=valor.datos_paciente[0].nombres
					valor.apellidoss=valor.datos_paciente[0].apellidos
					valor.tipo_examens=valor.tipo_examen[0].nombre
					valor.resultado=valor.examen.fecha_examen
				}); 
				return tabla
			},
		getDate(datetime) {
			let date = new Date(datetime);
			let dateString = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
			return dateString;
		},

		abrirDialog(id, estado) {
			let titulo = "";
			let color = "";
			if (estado === 0 || estado === false) {
				// cambiar de color al boton
				color = "success";
				titulo = "Confirmar activación";
			} else if (estado === 1 || estado === true) {
				color = "danger";
				titulo = "Confirmar desactivación";
			}
			this.id = id;
			this.estado = estado;
			this.$vs.dialog({
				type: "confirm",
				color: `${color}`,
				title: `${titulo}`,
				text: "¿Está seguro de llevar a cabo esta acción?",
				accept: this.cambiarEstado,
				cancel: this.close,
			acceptText: 'Aceptar',
			cancelText: 'Cancelar',
			});
		},
		cambiarEstado(color) {
			let titulo = "";
			if (this.estado === 0 || this.estado === false) {
			titulo = "Activado exitósamente";
			axios
				.put("/api/medicamento/activar", {
				id: this.id
				})
				.then(function(response) {
				console.log(response.data.message);
				})
				.catch(function(error) {
				console.log(error.response.data.message);
				});
			} else if (this.estado === 1 || this.estado === true) {
			titulo = "Desactivado exitósamente";
			axios
				.put("/api/medicamento/desactivar", {
				id: this.id
				})
				.then(function(response) {
				console.log(response.data.message);
				})
				.catch(function(error) {
				console.log(error.response.data.message);
				});
			}

			this.$vs.notify({
			color: "success",
			title: `${titulo}`,
			text: "La acción se realizo exitósamente"
			});
		},
		close() {
			let titulo = "Cancelado";
			let texto = "Cambio de estado cancelado";
			this.$vs.notify({
				color: "danger",
				title: `${titulo}`,
				text: `${titulo}`
			});
			this.index();
		},
		async index() {
			//async para que se llame cada vez que se necesite
			let me = this;
			const response = await axios
			.get(`/api/historialExamen/get?completo=true`)
			.then(function(response) {
				var respuesta = response.data;
                me.arrayData = respuesta.historialExamenes.data;
                me.codigoE = respuesta.historialExamenes.data[0].id;
                me.arrayData = me.traerDatos(me.arrayData);
                console.log("DATA");
                console.log(me.arrayData);
			})
			.catch(function(error) {
				console.log(error);
			});
		},
		exportToExcel () {
			import('@/vendor/Export2Excel').then(excel => {
			const list = this.arrayData
			const data = this.formatJson(this.headerVal, list)
			excel.export_json_to_excel({
				header: this.headerTitle,
				data,
				filename: this.fileName,
				autoWidth: this.cellAutoWidth,
				bookType: this.selectedFormat
			})

			this.clearFields()
			})
		},
		formatJson (filterVal, jsonData) {
			return jsonData.map(v => filterVal.map(j => {
			return v[j]
			}))
		},
		clearFields () {
			this.filename = ''
			this.cellAutoWidth = true
			this.selectedFormat = 'xlsx'
		},
	},
	mounted() {
        this.index();
	}
};
</script>

<style lang="scss">
/*! rtl:begin:ignore */
#dashboard-analytics {
  .greet-user {
    position: relative;

    .decore-left {
      position: absolute;
      left: 0;
      top: 0;
    }
    .decore-right {
      position: absolute;
      right: 0;
      top: 0;
    }
  }

  @media (max-width: 576px) {
    .decore-left,
    .decore-right {
      width: 140px;
    }
  }
}
/*! rtl:end:ignore */
</style>
