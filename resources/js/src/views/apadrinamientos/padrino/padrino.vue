<template>
			<div>
				<vx-card>
					<div class = "demo-alignment">
						<h2>Padrinos</h2>
						<vx-tooltip text = "Agregar nuevo registro"> <router-link to="/ingresar/padrino"> <vs-button radius type = "gradient" icon = "person_add" color = "primary" size="large" ></vs-button> </router-link>  </vx-tooltip>
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

					<vs-table stripe title="Padrinos" pagination max-items="7" search :data="arrayData" noDataText="No hay datos disponibles">
        				<template slot="header">
							<vs-button @click="activePrompt=true">Exportar</vs-button>
        				</template>
						<template slot="thead">
							<!-- <vs-th>Ver</vs-th> -->
							<vs-th>Nombre</vs-th>
							
              				<vs-th>Género</vs-th>
							<vs-th>Correo Electrónico</vs-th>
		
							<vs-th>Estado</vs-th>
							<vs-th>Acciones</vs-th>
						</template>

						<template slot-scope="{data}">
                			<vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
								<!-- <vs-td>
									<vx-tooltip text="Información Completa"> <vs-button radius color="dark" type="flat" icon="visibility" size="large"></vs-button></vx-tooltip>
								</vs-td> -->
								<vs-td>
									<div class="flex items-center">
										<vs-avatar :src="data[indextr].ruta_imagen" color="primary" class="flex-shrink-0 mr-2" size="30px"/>
										{{data[indextr].nombres + " " + data[indextr].apellidos}}
									</div>
								</vs-td>
								
								<vs-td :data="data[indextr].datos.genero">
                        			{{data[indextr].datos.genero== 1 ? 'Masculino' : 'Femenino'}}
                    			</vs-td>
								<vs-td :data="data[indextr].correo">
                        			{{data[indextr].correo}}
                    			</vs-td>
								<vs-td :data="data[indextr].estado">
                        			<vs-switch color="success" v-model="data[indextr].estado" @click="abrirDialog(data[indextr].id, data[indextr].estado)">
				                  		<span slot="on" >Activo</span>
				                  		<span slot="off">Desactivo</span>
			                  		</vs-switch>
                   				 </vs-td>
									<vs-td>
              						<div class="flex items-center">
									<vx-tooltip text="Editar"> <vs-button @click="$router.push('/editar/padrino/'+data[indextr].id)" radius color="dark" type="flat" icon="edit" size="large"> </vs-button>  </vx-tooltip>
									<vx-tooltip text="Apadrinamientos"> <vs-button radius color="dark" type="flat" icon="list" size="large" @click="openAlert('success',data[indextr])"> </vs-button>  </vx-tooltip>
								  </div>
								</vs-td>

							</vs-tr>


						</template>
					</vs-table>

				<vs-prompt
					:buttons-hidden="true"
					title="Niños apadrinados"
					:active.sync="abrirListado">
						<li v-for="(nino,index) in listadoApadrinamientos" v-bind:key="index">
							{{nino.codigo }} - {{nino.nombres + " " + nino.apellidos}} 
						</li>
				</vs-prompt>

				</vx-card>
			</div>

			

</template>


<script>

import VueApexCharts from 'vue-apexcharts'
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
//import analyticsData from './ui-elements/card/analyticsData.js'
import ChangeTimeDurationDropdown from '@/components/ChangeTimeDurationDropdown.vue'
import VxTimeline from '@/components/timeline/VxTimeline'
import Formulario from './formulariopadrin.vue'
import axios from 'axios'
import vSelect from 'vue-select'
import EditPadrino from './formulariopadrinedit.vue'

export default {
  data () {
    return {
	  search : '',
	  listadoApadrinamientos:[],
      arrayData: [],
	  nombres: '',
	  apellidos: '',
	  correo: '',
	  titulo:'Registrado exitosamente!',
	  id: 0,
	  estado: null,
	  fileName: '',
      formats:['xlsx', 'csv', 'txt'],
      cellAutoWidth: true,
	  selectedFormat: 'xlsx',
	  headerVal: ['id', 'nombres', 'apellidos','correo','estado'],
	  headerTitle: ['Id', 'Nombre', 'Apellidos','Correo','Estado'],
      activePrompt: false,
	  abrirListado: false,
	  activePrompt: false,
      'tableList': [
        'vs-th: Component',
        'vs-tr: Component',
        'vs-td: Component',
        'thread: Slot',
        'tbody: Slot',
        'header: Slot'
      ]
    }
  	},
  components: {
    VueApexCharts,
    StatisticsCardLine,
    ChangeTimeDurationDropdown,
    VxTimeline,
	Formulario,
	EditPadrino,
	vSelect
    
  },
  methods: {
	openAlert(color,padrino){
		this.listado(padrino)
	},
	async listado(padrino) {
		let me = this
		let l_tutorias=[]
			await axios.get(`/api/apadrinamiento/get?criterio=padrino_id&buscar=${padrino.id}&completo=true`)
			.then(function (response) {
				const respuesta = response.data
				l_tutorias = respuesta.apadrinamientos.data
				me.listadoApadrinamientos =[]
				l_tutorias.forEach(function(valor, indice, array){
					me.listadoApadrinamientos.push({codigo:valor.nino.codigo, nombres:valor.datos_nino[0].nombres,apellidos:valor.datos_nino[0].apellidos})
				});
        		me.abrirListado=true;
			})
		.catch(function (error) {
			console.log(error)
		})		
	},
	abrirDialog(id, estado){

		let titulo = '';
		let color = '';

		if(estado === 0 || estado === false){
			// cambiar de color al boton
			color = 'success'
			titulo = 'Confirmar activación'
		}

		else if(estado === 1 || estado === true){
			color = 'danger'
			titulo = 'Confirmar desactivación'
		}
		
		this.id = id
		this.estado = estado

		this.$vs.dialog({
			type:'confirm',
			color: `${color}`,
			title: `${titulo}`,
			text: '¿Está seguro de llevar a cabo esta acción?',
			accept: this.cambiarEstado,
			acceptText: 'Aceptar',
			cancelText: 'Cancelar',
		})

		this.index();
	},
	cambiarEstado(color){
		let titulo = ''
		
		if(this.estado === 0 || this.estado === false){
			titulo = 'Activado exitósamente'
			axios.put('/api/padrino/activar', {
				id: this.id
			})
			.then(function (response) {
				console.log(response.data.message)
			})
			.catch(function (error) {
				console.log(error.response.data.message)
			});
		}
		else if(this.estado === 1 || this.estado === true){
			titulo = 'Desactivado exitósamente'
			axios.put('/api/padrino/desactivar', {
				id: this.id
			})
			.then(function (response) {
				console.log(response.data.message)
			})
			.catch(function (error) {
				console.log(error.response.data.message)
			});
		}
		this.$vs.notify({
          color:'success',
          title:`${titulo}`,
          text:'La acción se realizo exitósamente'
		})
		this.index();
	},
	async index(){ //async para que se llame cada vez que se necesite
		let me = this;
		const response = await axios.get(
			`/api/padrino/get?completo=true`)
		.then(function (response) {
			var respuesta= response.data;
			me.arrayData = respuesta.padrinos.data;
			me.padrinos = me.traerNombre(me.arrayData)
		})
		.catch(function (error) {
			console.log(error);
		});
	},
	close(){
		let titulo = "Cancelado"
		let texto = "Cambio de estado cancelado"
		this.$vs.notify({
        color:'danger',
        title:`${titulo}`,
        text:`${titulo}`
	  })
	this.index();
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
        // Add col name which needs to be translated
        // if (j === 'timestamp') {
        //   return parseTime(v[j])
        // } else {
        //   return v[j]
        // }

        return v[j]
      }))
    },
	clearFields () {
      this.filename = ''
      this.cellAutoWidth = true
      this.selectedFormat = 'xlsx'
    },

    traerNombre (tabla) {
      tabla.forEach(function (valor, indice, array) {
        valor.nombres = valor.datos.nombres
        valor.apellidos = valor.datos.apellidos
      }) 
      return tabla
    }
  
  },
  
  mounted(){
    this.index();
  }
  
}
</script>

<style lang="scss">
/*! rtl:begin:ignore */
#dashboard-analytics {
  .greet-user{
    position: relative;

    .decore-left{
      position: absolute;
      left:0;
      top: 0;
    }
    .decore-right{
      position: absolute;
      right:0;
      top: 0;
    }
  }

  @media(max-width: 576px) {
    .decore-left, .decore-right{
      width: 140px;
    }
  }
}
/*! rtl:end:ignore */
</style>
