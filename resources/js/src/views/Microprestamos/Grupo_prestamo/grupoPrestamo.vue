<template>
   <vx-card >
       <div class = "demo-alignment">
           <h2>Grupos</h2>
         <router-link to="/microprestamo/Nuevogrupo">  <vx-tooltip text = "Agregar nuevo registro"> <vs-button radius type = "gradient" icon = "group_add" color = "primary" size = "large" ></vs-button>  </vx-tooltip></router-link>
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

  <vs-table stripe title="Grupos" pagination max-items="7" search :data="arrayData" noDataText="No hay datos disponibles">
      <template slot="header">
        <vs-button @click="activePrompt=true">Exportar</vs-button>
      </template>

    <template slot="thead">
      <vs-th>Ver</vs-th>
      <vs-th sort-key="nombre">Id</vs-th>
      <vs-th sort-key="nombre">Nombre</vs-th>
      <vs-th sort-key="us">Descripción</vs-th>
      <vs-th sort-key="website">Último Préstamo</vs-th>
      <vs-th sort-key="id">Préstamo Actual</vs-th>
      <vs-th sort-key="Fecha de creación">Fecha de creación</vs-th>
      <vs-th sort-key="estado">Estado</vs-th>
      
      <vs-th>Acciones</vs-th>
    </template>

    <template slot-scope="{data}">
      <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
        <vs-td>
					<vx-tooltip text="Información Completa"> <vs-button radius color="dark" type="flat" icon="visibility" size="large" @click="$router.push('/microprestamo/Vergrupo/'+data[indextr].id)"></vs-button></vx-tooltip>			
				</vs-td>

        <vs-td :data="data[indextr].id">
          {{ data[indextr].id }}
        </vs-td>

        <vs-td :data="data[indextr].nombre">
          {{ data[indextr].nombre }}
        </vs-td>

        <vs-td :data="data[indextr].descripcion">
          {{ data[indextr].descripcion }}
        </vs-td>

        <vs-td>
         {{ currency(data[indextr].cantidad_ultimo_prestamo) }}
        </vs-td>

        <vs-td>
          {{ currency(data[indextr].cantidad_prestamo_actual)}}
        </vs-td>
        <vs-td :data="data[indextr].created_at">
          {{getDate( data[indextr].created_at)}}
        </vs-td>

        <vs-td :data="data[indextr].estado">
           <vs-switch color="success" v-model="data[indextr].estado" @click="abrirDialog(data[indextr].id, data[indextr].estado)">
             <span slot="on" >Activo</span>
             <span slot="off">Desactivo</span>
           </vs-switch>
        </vs-td>

        <vs-td>
           <vx-tooltip text="Editar"> <vs-button radius color="dark" type="flat" icon="edit" size="large" @click="$router.push('/microprestamo/editarGrupo/'+data[indextr].id)"> </vs-button>  </vx-tooltip>
        </vs-td>

      </vs-tr>
    </template>
  </vs-table>
  </vx-card>
</template>

<script>
import axios from 'axios'
import vSelect from 'vue-select'

export default {
  data () {
    return {
        
      'arrayData':[],
      'selected': [],
      'tableList': [
        'vs-th: Component',
        'vs-tr: Component',
        'vs-td: Component',
        'thread: Slot',
        'tbody: Slot',
        'header: Slot'
      ],
      'fileName': '',
      'formats':['xlsx'],
      'cellAutoWidth': true,
	  'selectedFormat': 'xlsx',
	  'headerVal': ['id', 'nombre', 'descripcion', 'cantidad_ultimo_prestamo', 'cantidad_prestamo_actual', 'interes_ultimo_prestamo', 'created_at'],
	  'headerTitle': ['Id', 'Nombre', 'Descripción', 'Último préstamo', 'Préstamo actual', 'Interes del préstamo', 'Fecha de creación'],
      'activePrompt': false
    }
  },

  components:{
    vSelect
  },
  methods:{
    currency (numero) {
      const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GTQ'
      })
      const mil = formatter.format(numero)
      return mil
    },
    getDate (datetime) {
      const date = new Date(datetime)
      const dateString = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`
      return dateString
    },
    async index () {
      const me = this
      this.abrir_editar = false
      const response = await axios.get('/api/grupoPrestamo/get?completo=true')
        .then(function (response) {
          const respuesta = response.data
          me.arrayData = respuesta.grupoPrestamos.data
        })
        .catch(function (error) {
          console.log(error)
        })
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
    abrirDialog (id, estado) {
      let titulo = ''
      let color = ''
      if (estado === 0 || estado === false) {
        // cambiar de color al boton
        color = 'success'
        titulo = 'Confirmar activación'
      } else if (estado === 1 || estado === true) {
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
        cancel: this.close,
			acceptText: 'Aceptar',
			cancelText: 'Cancelar',
      })
    },
    cambiarEstado (color) {
      let titulo = ''
      if (this.estado === 0 || this.estado === false) {
        titulo = 'Activado exitósamente'
        axios.put('/api/grupoPrestamo/activar', {
          id: this.id
        })
          .then(function (response) {
            console.log(response.data.message)
          })
          .catch(function (error) {
            console.log(error.response.data.message)
          })
      } else if (this.estado === 1 || this.estado === true) {
        titulo = 'Desactivado exitósamente'
        axios.put('/api/grupoPrestamo/desactivar', {
          id: this.id
        })
          .then(function (response) {
            console.log(response.data.message)
          })
          .catch(function (error) {
            console.log(error.response.data.message)
          })
      }

      this.$vs.notify({
        color:'success',
        title:`${titulo}`,
        text:'La acción se realizo exitósamente'
      })
    },
    close () {
      const titulo = 'Cancelado'
      const texto = 'Cambio de estado cancelado'
      this.$vs.notify({
        color:'danger',
        title:`${titulo}`,
        text:`${titulo}`
      })
      this.index()
    }
  },
  mounted () {
    this.index()
  }
}
</script>
        