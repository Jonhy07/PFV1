<template>
	<vx-card>
		<div class="vx-col md:w-1/3 w-full mt-5">
				<router-link  to="/apadrinamiento/padrino"><vs-button radius type="border" class="w-full" icon-pack="feather" icon="icon-corner-up-left" icon-no-border></vs-button></router-link>
		</div>
		<div class="mt-5">
			<form-wizard title="INGRESO DE PADRINO" color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" :subtitle="null" finishButtonText="Enviar" back-button-text="Atrás" next-button-text="Siguiente" @on-complete="formSubmitted">
				<vs-divider position="right">PID&#174;</vs-divider>		
				<tab-content title="Paso 1" class="mb-5" icon="feather icon-user-plus" :before-change="validateStep1">
				<!-- tab 1 content -->
				<form data-vv-scope="step-1">
				<div class="vx-row">
				<div class="vx-col md:w-1/2 w-full mt-5">
					<vs-input label="Nombres" v-model="nombres" class="w-full" icon-pack="feather" icon="icon-user" name="nombres" v-validate="'required|alpha_spaces|max:30'" />
					<span class="text-danger">{{ errors.first('step-1.nombres') }}</span>
				</div>

				<div class="vx-col md:w-1/2 w-full mt-5">
					<vs-input label="Apellidos"  v-model="apellidos" class="w-full" icon-pack="feather" icon="icon-user" name="apellidos" v-validate="'required|alpha_spaces|max:30'" />
					<span class="text-danger">{{ errors.first('step-1.apellidos') }}</span>
				</div>

				<div class="vx-col md:w-1/2 w-full mt-5">
					<vs-input label="ID"  v-model="CUI" class="w-full" icon-pack="feather" icon="icon-hash" name="cui" v-validate="'max:15|alpha_num'" />
					<span class="text-danger">{{ errors.first('step-1.cui') }}</span>
				</div>

				<div class="vx-col md:w-1/2 w-full mt-5">
					<small class="date-label">Género</small>
					<ul class="demo-alignment">
						<li>
							<vs-radio v-validate="'required|included:1,0'" name="radio" color="rgb(0, 170, 228)" v-model="genero" vs-value="1" selected>Masculino</vs-radio>
						</li>
						<li>
							<vs-radio name="radio" color="rgb(255, 0, 128)" v-model="genero" vs-value="0">Femenino</vs-radio>
						</li>
					</ul>
					<span class="text-danger">{{ errors.first('step-1.radio') }}</span>

				</div>

				<div class="vx-col md:w-1/2 w-full mt-5">
				<div class="my-4">
					<small class="date-label">Fecha de nacimiento</small>
					<datepicker :format="dateFormat" :language="$vs.rtl ? langEn : langEn" name="fecha"  v-model="fecha_nacimiento"></datepicker>
					

				</div>
				</div>
				
				<div class="vx-col md:w-1/2 w-full mt-5">
					<vs-input label="Dirección"  v-model="direccion" class="w-full" icon-pack="feather" icon="icon-map-pin" name="direccion" />
					
				</div>

				<div class="vx-col md:w-1/2 w-full mt-5"> 
					<vs-input label="Número de teléfono"  v-model="numero_telefono" class="w-full" icon-pack="feather" icon="icon-phone" name="telefono" v-validate="'max:15|numeric'"/>
					<span class="text-danger">{{ errors.first('step-1.telefono') }}</span>

				</div>

				</div>
				</form>
			</tab-content>

			<!-- tab 2 content -->
			<tab-content title="Paso 2" class="mb-5" icon="feather icon-file-plus" :before-change="validateStep2">
				<form data-vv-scope="step-2">
					<div class="vx-row">
						<div class="vx-col md:w-1/2 w-full">
							<template>
								<vs-upload automatic action="/api/padrino/imagen" limit='1' :headers="head" fileName='photos' @on-success="respuesta" @on-delete="vaciar" text="Imagen de perfil"/>
							</template> 
						</div>
						<div class="vx-col md:w-1/2 w-full mt-5">
							<vs-input type="email" label="Correo"  v-model="correo" class="w-full"  icon-pack="feather" icon="icon-mail" name="correo" v-validate="'required|email|max:100'" />
							<span class="text-danger">{{ errors.first('step-2.correo') }}</span>
						</div>
					</div>
				</form>
			</tab-content>

			</form-wizard>
		</div>
		<div class="vx-col md:w-1/3 w-full mt-5">
			<router-link  to="/apadrinamiento/padrino"><vs-button type="gradient" class="w-full" icon-pack="feather" icon="icon-corner-up-left" icon-no-border>Regresar</vs-button></router-link>
		</div>
	</vx-card>
</template>

<script>
import { ValidationProvider } from 'vee-validate';
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import vSelect from 'vue-select'
import Datepicker from 'vuejs-datepicker'
import axios from 'axios'
// For custom error message
import { Validator } from 'vee-validate';


const dict = {
  custom: {
    nombres: {
      required: 'El campo nombres es requerido',
	  alpha_spaces: 'El campo solo debe de contener letras y espacios',
	  max: 'Este campo solo acepta hasta 30 caracteres',
    },
    apellidos: {
      required: 'El campo apellidos es requerido',
	  alpha_spaces: 'El campo solo debe de contener letras y espacios',
	  max: 'Este campo solo acepta hasta 30 caracteres',
    },
    correo: {
      required: 'El campo correo es requerido',
	  email: 'Ingrese un correo válido',
	  max: 'Este campo solo acepta hasta 100 caracteres',
    },
    cui: {
	  alpha_num: 'El campo solo debe de contener letras y números',
	  max: 'Este campo solo acepta hasta 15 caracteres',
	},
	fecha: {
      required: 'El campo fecha de nacimiento es requerido',
	},
	direccion: {
	  required: 'El campo dirección es requerido',
	  max: 'Este campo solo acepta hasta 254 caracteres',
	},
	telefono: {
	  max: 'Este campo solo acepta hasta 15 caracteres',
	  numeric: 'El campo solo debe de contener números',
	},
	radio: {
	  required: 'Seleccione una opción',
	  included: 'Seleccione una opción',
    },
  }
}

Validator.localize('es', dict)
import { es } from 'vuejs-datepicker/src/locale'
export default {
  data () {
    return {
		nombres: "",
		apellidos: "",
		direccion: "",
		genero:'',
		fecha_nacimiento:"",
		CUI:'',
		numero_telefono:'',
		correo:'',
		ruta_imagen:'',
		sectores: [],
		sector_id:'',
		langEn: es,
		head:{
			"imagenanterior":""	
		},
		dateFormat : 'yyyy-MM-dd'
	}
  },
  methods: {
	vaciar(){
		this.imagen_perfil='';
	},
	respuesta(e){
		this.ruta_imagen=e.currentTarget.response.replace(/['"]+/g, '')
		this.head.imagenanterior=this.ruta_imagen
    },
    getDate(datetime) {
        let date = new Date(datetime);
        let dateString = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
        this.fecha_nacimiento = dateString
      },
    validateStep1 () {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-1').then(result => {
          if (result) {
            resolve(true)
          } else {
			reject('correct all values')
			this.$vs.notify({
			color:'danger',
			title:'Error en validación!',
			text:'Ingrese todos los campos correctamente'
			});
          }
        })
      })
    },
    validateStep2 () {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-2').then(result => {
          if (result) {
            resolve(true)
          } else {
			reject('correct all values')
			this.$vs.notify({
			color:'danger',
			title:'Error en validación!',
			text:'Ingrese todos los campos correctamente'
			});
          }
        })
      })
    },
    formSubmitted () {
		if (this.fecha_nacimiento !== '') {
           this.getDate(this.fecha_nacimiento)
		}
		
		if (this.ruta_imagen === ''){
			this.ruta_imagen= "default.png"
		}
		axios.post("/api/padrino/post/",{
		nombres:this.nombres,
		apellidos:this.apellidos,
		CUI:this.CUI,
		numero_telefono:this.numero_telefono,
		correo:this.correo,
		ruta_imagen:'/storage/public/' + this.ruta_imagen,
		genero:this.genero,
		fecha_nacimiento: this.fecha_nacimiento,
		direccion:this.direccion,
		//SECTOR EXTRANJERO
		sector_id:1
	}).then(function(response) {
      console.log(response)
		})
		.catch(function(error) {
		console.log(error)
        });
        this.$emit('cerrado','Se cerró el formulario');
        this.$vs.notify({
          color:'success',
          title:'Padrino registrado',
          text:'La acción se realizo exitósamente'
        });
        this.$router.push('/apadrinamiento/padrino');
    },
    successUpload(){
      this.$vs.notify({
					color:'success',
					title:'Imagen subida',
					text:'Acción realizada exitósamente!'
				});
    },
  },
  components: {
		ValidationProvider,
		FormWizard,
		TabContent,
		Datepicker,
		vSelect,
  },
}
</script>