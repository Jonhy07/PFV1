<template>
  <div class="vx-row">

    <!-- HORIZONTAL LAYOUT -->
    <div class="vx-col md:w-1/2 w-full mb-base">
       <vs-alert v-if="alerta==true" color="success" title="Información" active="true">
           El pago de la vivienda se ha completado 
        </vs-alert>
      <vx-card title="Ingresar Abono">
		<vs-divider position="right">PID&#174;</vs-divider>
        <div class="vx-row mb-6">
          <div class="vx-col sm:w-1/3 w-full">
            <span>Nombre del propietario</span>
          </div>
          <div class="vx-col sm:w-2/3 w-full">
            <v-select label="encargado_nombreCompleto" :options="encargados" v-model="vivienda_id"  @input="buscar" @change="onChange($event)" :dir="$vs.rtl ? 'rtl' : 'ltr'" name="encargado" v-validate="'required'"/>
            <span class="text-danger text-sm" v-show="errors.has('encargado')">{{ errors.first('encargado') }}</span>
            <span></span>
          </div>
        </div>
        <div class="vx-row mb-6">
          <div class="vx-col sm:w-1/3 w-full">
            <span>Cantidad de abono(Q)</span>
          </div>
          <div class="vx-col sm:w-2/3 w-full">
            <vs-input class="w-full"  v-model="cantidad" name="cantidad" v-validate="'required|max:5'"/>
            <span class="text-danger text-sm" v-show="errors.has('cantidad')">{{ errors.first('cantidad') }}</span>
          </div>
        </div>
        <div class="vx-row mb-6">
          <div class="vx-col sm:w-1/3 w-full">
            <span>Descripción</span>
          </div>
          <div class="vx-col sm:w-2/3 w-full">
            <vs-textarea class="w-full" name="descripcion" v-model="descripcion" v-validate="'required|max:150'" />
            <span class="text-danger text-sm" v-show="errors.has('descripcion')">{{ errors.first('descripcion') }}</span>
          </div>
        </div>
        <div class="vx-row mb-6">
          <div class="vx-col sm:w-1/3 w-full">
            <span>Fecha</span>
          </div>
          <div class="vx-col sm:w-2/3 w-full">
            <span class="text-2xl leading-none font-medium text-primary mr-4"> {{fecha}}</span>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-2/3 w-full ml-auto">
            <vs-button type="gradient" icon-pack="feather" icon="icon-save" class="mr-3 mb-2" @click="enviarForm">Aceptar</vs-button>
            <vs-button color="warning" icon="format_clear" type="border" class="mb-2" @click="limpiar">Limpiar</vs-button>
          </div>
        </div>
      </vx-card>
    </div>
    
    <div class="vx-col lg:w-1/3 w-full">
    <div id="invoice-page">
                        <vx-card id="invoice-container">
                          <div class="flex justify-between mb-2">
                               <img src="@assets/images/logo/logopid.png" height="90px" width="150px" alt="PID Guatemala">
                               <p class="text-primary">Partners in Development</p>
                               
                            </div>

                            <span>Comprobante pago de vivienda</span>
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold">Comprobante No.</span>
                                <span class="font-medium text-primary cursor-pointer">{{nRecibo}}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey"> {{nombreSeleccionado }} </span>
                                <span class="text-grey"> {{getDate(fecha)}}</span>
                            </div>
                            <vs-divider position="right">PID&#174;</vs-divider>
                            <p class="font-semibold mb-3">Detalles</p>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey">Número de vivienda</span>
                                <span>{{vivienda_id.id}}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey">Descripción</span>
                                <span>{{descripcion}}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey">Costo total de vivienda</span>
                                <span>{{currency(costoV)}}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey">Pendiente por pagar</span>
                                <span v-if="deuda > 0">{{currency(deuda)}}</span>
                                <span v-else> {{currency(costoV)}}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-grey">Cantidad de abono</span>
                                <span class="text-success">-{{currency(cantidad)}}</span>
                            </div>

                            <vs-divider />

                            <div class="flex justify-between font-semibold mb-3">
                                <span>Total de deuda </span>
                                <span>{{currency(total)}}</span>
                            </div>

                           
                        </vx-card><br>
                         <vs-button type="gradient" icon-pack="feather" icon="icon-printer" class="w-full" @click="printInvoice" v-if="imprimir == true">Imprimir</vs-button>
                    </div>
                  </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import { es } from 'vuejs-datepicker/src/locale'
import vSelect from 'vue-select'
import axios from 'axios'
import Ls from '../../../services/ls';
//validar
import { Validator } from 'vee-validate'
const dict = {
  custom: {
    cantidad: {
      	  required: 'El campo cantidad de abono es requerido',
      	  numeric: 'El campo solo debe de contener números',
      max: 'Este campo solo acepta hasta 5 dígitos'
    },
    descripcion: {
      required:'El campo descripción es requerido',
      max:'No se aceptan más de 150 caracteres'
    },
    encargado:{
      required:'Seleccione una persona'
    }
  }
}
Validator.localize('es', dict)

export default{
  data () {
    return {
      arrayData:[],
      encargados: [],
      arrayV:[],
      arrayA:[],
      cantidad:'',
      descripcion:'',
      fecha:'',
      langEn: es,
      input30: '',
      date: null,
      vivienda_id:'',
      nombre_vivienda:'',
      encargado_nombres:'',
      encargadoVivienda_id:'',
      total:'',
      deuda:'',
      costoV:'',
      nRecibo:'',
      imprimir:false,
      alerta:false,
      configdateTimePicker: {
        date: null,
        inline: true
	  },
	  nombreSeleccionado:'Nombre de la persona'
    }
  },
  created () {
    setInterval(this.getNow, 1000)
  },
  methods:{
    enviarForm () {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.guardar()
        } else {
          this.$vs.notify({
            color:'danger',
            title:'Error en validación!',
            text:'Ingrese correctamente todos los datos'
          })
        }
      })

    },
    currency (numero) {
      const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GTQ'
      })
      const mil = formatter.format(numero)
      return mil
    },
    traerDatosEncargados (tabla) {

      tabla.forEach(function (valor, indice, array) {
        valor.encargado_nombres = valor.datos_residente[0].nombres
	    	valor.encargado_apellidos = valor.datos_residente[0].apellidos
		    valor.encargado_nombreCompleto = `${valor.encargado_nombres  } ${  valor.encargado_apellidos}`
      }) 

      return tabla
    },
    async importarEncargados () { //async para que se llame cada vez que se necesite
      const me = this
      const response = await axios.get(
        '/api/vivienda/get?completo=false')
        .then(function (response) {
          const respuesta = response.data
          me.arrayData = respuesta.viviendas.data
          me.encargados = me.traerDatosEncargados(me.arrayData)
        })
        .catch(function (error) {
          console.log(error)
        })
    },
	
    async buscar () {
      const me = this
	  this.id_recibido = this.vivienda_id.id
	  this.nombreSeleccionado = `${this.vivienda_id.encargado_nombres  } ${  this.vivienda_id.encargado_apellidos}`
      const response = await axios.get(
        `/api/vivienda/get?&criterio=id&buscar=${this.id_recibido}&completo=true`)
        .then(function (response) {
          const respuesta = response.data
          me.arrayV = respuesta.viviendas.data[0]
          me.nombre_vivienda = me.arrayV.direccion
          me.costoV = me.arrayV.costo_total
        })
        .catch(function (error) {
          console.log(error)
          me.$vs.notify({
            color:'danger',
            title:'Error!',
            text:'Error al buscar datos de la persona'
          })
        })
      this.deuda = 0
      this.imprimir = false
    },
    async buscarAbono () {
      const me = this
      this.id_recibido = this.vivienda_id.id
      const response = await axios.get(
        `/api/historialAbonoVivienda/get?&criterio=vivienda_id&buscar=${this.id_recibido}&completo=true`)
        .then(function (response) {
          const respuesta = response.data
          me.arrayA = respuesta.historialAbonoViviendas.data[0]
          me.deuda = me.arrayA.abono.cantidad_restante
        })
        .catch(function (error) {
          console.log(error)
        })
    },
    
    getDate (datetime) {
      const date = new Date(datetime)
      const dateString = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`
      return dateString
    },

    guardar () {
      const me = this
      axios.post('/api/historialAbonoVivienda/post/', {
        cantidad_abono:this.cantidad,
        cantidad_restante:this.total,
        descripcion:this.descripcion,
        fecha_pago:this.getDate(this.fecha),
		user_id:parseInt(Ls.get('auth.id_usuario')),
        vivienda_id:this.vivienda_id.id

      }).then(function (response) {
        console.log(response.data.id)
        me.seterResponse(response.data.id)
        me.desactivar()
        me.imprimir = true
        me.$vs.notify({
          color:'success',
          title:'Abono registrado!',
          text:'La acción se realizo exitósamente'
        })
      })
        .catch(function (error) {
          console.log(error)
          me.$vs.notify({
            color:'danger',
            title:'Error en ingreso!',
            text:'Erro al registrar el abono'
          })
        })
    },
    desactivar () {
      const me = this
      this.id_recibido = this.vivienda_id.id
      if (this.total === 0) {
        axios.put('/api/vivienda/desactivar', {
          id: this.id_recibido
        })
          .then(function (response) {
            console.log(response.data.message)
            me.alerta = true        
          })
          .catch(function (error) {
            console.log(error.response.data.message)
            me.$vs.notify({
              color:'danger',
              title:'Error!',
              text:'Erro al actualizar la finalización del pago completo de vivienda'
            })
          })
      }

    },
    limpiar () {
      this.deuda = ''
      this.total = ''
      this.vivienda_id = ''
      this.cantidad = ''
      this.costoV = ''
      this.descripcion = ''
      this.alerta = false
      this.imprimir = false
    },
    seterResponse (id) {
      this.nRecibo = id
    },
    Calcular () {
      if (this.deuda === 0) {
        this.total = this.costoV - this.cantidad
      } else this.total = this.deuda - this.cantidad
      return this.total
    },
    getNow () {
      const today = new Date()
      const date = `${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}`
      const time = `${today.getHours()  }:${  today.getMinutes()  }:${  today.getSeconds()}`
      const dateTime = `${date } ${ time}`
      this.fecha = dateTime
    },
    printInvoice () {
      window.print()
    }

  },
  mounted () {
    this.importarEncargados()
    this.$emit('setAppClasses', 'invoice-page')
  },
  watch:{
    vivienda_id () {
      this.buscarAbono()
    },

    cantidad () {
      this.Calcular()
    }
    
  },
  computed:{
  },
  components: {
    Datepicker,
    flatPickr,
    vSelect
  }
}
</script>

<style lang="scss">
@media print {
  .invoice-page {
    * {
      visibility: hidden;
    }

    #content-area {
      margin: 0 !important;
    }

    .vs-con-table {
      .vs-con-tbody {
        overflow: hidden !important;
      }
    }

    #invoice-container,
    #invoice-container * {
      visibility: visible;
    }
    #invoice-container {
      position: absolute;
      left: 0;
      top: 0;
      box-shadow: none;
    }
  }
}

@page {
  size: auto;
}
</style>
