<template>
  <div>
    <vx-card>

        <div class = "demo-alignment">
						<h2>Micropréstamos</h2>
						<vx-tooltip text = "Agregar nuevo registro"> <vs-button radius type = "gradient" icon-pack = "feather" icon = "icon-plus" @click="aNuevo" color = "primary" size = "large" ></vs-button>  </vx-tooltip>
					</div>
					           									<vs-divider position="right">PID&#174;</vs-divider>

		<vs-prompt title="Exportar a Excel" class="export-options" @cancle="clearFields" @accept="exportToExcel" accept-text="Exportar" cancel-text="Cancelar" @close="clearFields" :active.sync="activePrompt">
   
     
        <vs-input v-model="fileName" placeholder="Nombre de archivo" class="w-full" />
        <v-select v-model="selectedFormat" :options="formats" class="my-4" />
        <div class="flex">
			<span class="mr-4">Ancho automático de celda:</span>
			<vs-switch v-model="cellAutoWidth">Cell Auto Width</vs-switch>
        </div>
    </vs-prompt>
      <vs-table stripe pagination max-items="7" search :data="arrayData" noDataText="No hay datos disponibles">
         <template slot="header">
          <vs-button @click="activePrompt=true">Exportar</vs-button>
        </template>
        <template slot="thead">
            <vs-th>Ver</vs-th>
            <vs-th>Id</vs-th>
            <vs-th>Nombre del grupo</vs-th>
		  	<vs-th>Total</vs-th>
		    <vs-th>Interés</vs-th>
            <vs-th>Fecha</vs-th>
            <vs-th>Duración</vs-th>
            <vs-th>Día de Pago</vs-th>
            <vs-th>Mora</vs-th>
			<vs-th>Estado</vs-th>
            <vs-th>Acciones</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
				<vx-tooltip text="Información Completa"> <vs-button radius color="dark" type="flat" icon="visibility" size="large" @click="$router.push('/microprestamo/Vermicroprestamo/'+data[indextr].id)"></vs-button></vx-tooltip>			
			</vs-td>
            <vs-td>{{data[indextr].id}}</vs-td>
            <vs-td>{{data[indextr].nombreMicroprestamo}}</vs-td>
            <vs-td>{{ currency(data[indextr].total) }}</vs-td>
            <vs-td>% {{ data[indextr].interes }}</vs-td>
            <vs-td>{{ data[indextr].fecha_inicio}}</vs-td>
            <vs-td>{{ data[indextr].duracion}} meses</vs-td>
            <vs-td>{{ data[indextr].dia_pago}}</vs-td>
            <vs-td>{{ currency(data[indextr].mora_por_atraso)}}</vs-td>
              <vs-td :data="data[indextr].estado">
                <vs-switch
                  color="success"
                  v-model="data[indextr].estado"
                  @click="abrirDialog(data[indextr].id, data[indextr].estado)">
                  <span slot="on">Activo</span>
                  <span slot="off">Desactivo</span>
                </vs-switch>
              </vs-td>
      <vs-td>
        <div class="flex items-center">
				<vx-tooltip text="Editar"> <vs-button radius color="dark" type="flat" icon="edit" size="large" @click="$router.push('/editar/microprestamo/'+data[indextr].id)"> </vs-button>  </vx-tooltip>
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
import axios from "axios";
import vSelect from 'vue-select'
export default {
  data() {
    return {
      //Aqui van a guardar todas su variables.
      //rols: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      search: "",
      arrayData: [],
       fileName: '',
      formats:['xlsx', 'csv', 'txt'],
      cellAutoWidth: true,
	    selectedFormat: 'xlsx',
	    headerVal: ['id', 'nombreMicroprestamo','total', 'interes','fecha_inicio','duracion', 'dia_pago', 'mora_por_atraso', 'estado' ],
	    headerTitle: ['Id', 'Nombre del grupo','Total', 'Interés', 'Fecha','Duración', 'Día de pago', 'Mora', 'Estado'],
      activePrompt: false,
      nombre: "",
      fecha: "",
      genero: "",
      switch2: false,
      nino_id:0,
      padrino_id:0,
      abrir_editar:false,
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
    currency (numero) {
      const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GTQ'
      })
      const mil = formatter.format(numero)
      return mil
    },
      aNuevo () {
		 this.$router.push('/microprestamo/ingresar')
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
          .put("/api/microprestamo/activar", {
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
          .put("/api/microprestamo/desactivar", {
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
      this.abrir_editar=false
      const response = await axios
        .get(`/api/microprestamo/get?completo=true`)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.microprestamos.data;
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