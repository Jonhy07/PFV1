<template>
	<div>
		<vx-card>
			<div class="vx-col md:w-1/3 w-full mt-5">
<vs-button @click="goBack" radius type="border" class="w-full" icon-pack="feather" icon="icon-corner-up-left" icon-no-border></vs-button>
    </div>
			<form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" title="ACTUALIZACIÓN DE NIÑO" subtitle="" finishButtonText="Enviar" back-button-text="Atrás" next-button-text="Siguiente" @on-complete="formSubmitted">
				<vs-divider position="right">PID&#174;</vs-divider>
				<tab-content title="Paso 1" class="mb-5" icon="feather icon-user-plus" :before-change="validateStep1">

				<!-- tab 1 content -->
					<form data-vv-scope="step-1">
						<div class="vx-row">
							
							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-hash" icon-no-border label-placeholder="Código" v-model="codigoT" name="codigo" v-validate="'required|alpha_num|max:20'"/>
									<span class="text-danger">{{ errors.first('step-1.codigo') }}</span>
								</div>
							</div>


							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-user" icon-no-border label-placeholder="Nombres" v-model="nombresT" name="nombres" v-validate="'required|alpha_spaces|max:30'"/>
									<span class="text-danger">{{ errors.first('step-1.nombres') }}</span>
								</div>
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-user" icon-no-border label-placeholder="Apellidos" v-model="apellidosT" name="apellidos" v-validate="'required|alpha_spaces|max:30'"/>
									<span class="text-danger">{{ errors.first('step-1.apellidos') }}</span>
								</div>
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="my-4">
									<small class="date-label">Fecha Nacimiento</small>
									<datepicker :language="$vs.rtl ? langEn : langEn" name="fecha" v-model="fecha_nacimientoT" v-validate="'required'"></datepicker>
									<span class="text-danger">{{ errors.first('step-1.fecha') }}</span>
								</div>
							</div>

							<!-- <div class="vx-col md:w-1/2 w-full mt-5">
								<div class="my-4">
									<small class="date-label">Fecha Ingreso</small>
									<datepicker :language="$vs.rtl ? langEn : langEn" name="end-date" v-model="fecha_ingresoT"></datepicker>
								</div>
							</div> -->

							<div class="vx-col md:w-1/2 w-full mt-5">
								<small class="date-label">Género</small>
								<ul class="demo-alignment">
										<li>
											<vs-radio color="rgb(0, 170, 228)" v-model="generoT" vs-value="1" selected>Masculino</vs-radio>
										</li>
										<li>
											<vs-radio color="rgb(255, 0, 128)" v-model="generoT" vs-value="0">Femenino</vs-radio>
										</li>
								</ul>
							</div>
							
							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-home" icon-no-border label-placeholder="Dirección" v-model="direccionT" name="direccion" v-validate="'required|max:254'"/>
									<span class="text-danger">{{errors.first('step-1.direccion') }}</span>
								</div>
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-briefcase" icon-no-border label-placeholder="Ocupación" v-model="ocupacionT" name="ocupacion" v-validate="'max:150'"/>
									<span class="text-danger">{{errors.first('step-1.ocupacion') }}</span>
								</div>
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-coffee" icon-no-border label-placeholder="Actividades" v-model="actividadesT" name="actividades" v-validate="'max:150'"/>
									<span class="text-danger">{{errors.first('step-1.actividades') }}</span>
								</div>
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<small class="date-label">Sector</small>
								<v-select label="nombre" :options="sectoresT" v-model="sector_idT" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<small class="date-label">Escuela</small>
								<v-select label="nombre" :options="escuelasT" v-model="escuela_idT" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
							</div>

							<div class="vx-col md:w-1/2 w-full mt-5">
								<div class="vx-col w-full">
									<vs-input class="w-full" icon-pack="feather" icon="icon-file-text" icon-no-border label-placeholder="Grado" v-model="gradoT" name="grado" v-validate="'max:50'"/>
									<span class="text-danger">{{errors.first('step-1.grado') }}</span>
								</div>
							</div>
						</div>
					</form>
				</tab-content>

    <!-- tab 2 content -->
    <tab-content title="Paso 2" class="mb-5" icon="feather icon-file-plus" :before-change="validateStep2">
		<form data-vv-scope="step-2">
			<div class="vx-row">
				<div class="vx-col md:w-1/2 w-full mt-5">
					<template>
						<div class="vx-col md:w-1/2 w-full mt-5">
							<img :src="imagen_perfil_antigua"  width="100" height="100" class="responsive">
							<vx-tooltip text="Editar Imagen"> <vs-button class="mr-4" type="border" icon-pack="feather" color="#1551b1" icon="icon-edit" radius  @click="editarImagen"></vs-button> </vx-tooltip>
							<vs-upload v-if="mostrarEditar" automatic action="/api/nino/imagen" limit='1' :headers="head" fileName='photos' @on-success="respuesta" @on-delete="vaciar" text="Imagen de perfil" />
						</div>
					</template>
				</div>
			</div>
		</form>
    </tab-content>

 
  </form-wizard>


  <div class="vx-col md:w-1/3 w-full mt-5">
<vs-button @click="goBack"  type="gradient" class="w-full" icon-pack="feather" icon="icon-corner-up-left" icon-no-border>Regresar</vs-button>
    </div>


</vx-card>
</div>
</template>

<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import vSelect from 'vue-select'

// For custom error message
import { Validator } from 'vee-validate';
const dict = {
  custom: {
	  codigo:{
		required: 'El campo código es requerido',
		alpha_num: 'El campo solo debe de contener letras y números',
		max: 'Este campo solo acepta hasta 20 caracteres',
	},
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
    direccion: {
	  required: 'El campo dirección es requerido',
	  max: 'Este campo solo acepta hasta 254 caracteres',
    },
    fecha: {
      required: 'El campo fecha es requerido',
	},
	ocupacion: {
	  max: 'Este campo solo acepta hasta 150 caracteres',
	},
    actividades: {
	  max: 'Este campo solo acepta hasta 150 caracteres',
	},
	grado: {
	  max: 'Este campo solo acepta hasta 50 caracteres',
	},
	
  }
};
Validator.localize('en', dict);

import Datepicker from 'vuejs-datepicker'
import axios from 'axios'
// register custom messages
import { es } from 'vuejs-datepicker/src/locale'

export default {
  data() {
    return {
		nombresT: "",
		apellidosT: "",
		direccionT: "",
		fecha_nacimientoT: "",
		fecha_ingresoT: "",
		generoT:"",
		codigoT:"",
		ocupacionT:"",
		actividadesT:"",
		gradoT:"",
		ruta_imagen:'',
		sector_idT:0,
		escuela_idT:0,
		numero_telefono:'',
	//   id_recibido:0,
		sectoresT:[],
		escuelasT:[],
		titulo:'Actualización registrada!',
		langEn: es,
		codigo:'',
		mostrarEditar:false,
		imagen_perfil_antigua:'',
		head:{
			"imagenanterior":""	
		},
    }
  },
  methods: {
	  	goBack(){
			this.$router.go(-1)
	    },
		getDate(datetime) {
        	let date = new Date(datetime);
        	let dateString = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
			return dateString;
		},
		async index(){ 
			let me = this;
			this.id_recibido = this.$route.params.id;
			const response = await axios.get(
				`/api/nino/get?&criterio=id&buscar=${this.id_recibido}&completo=true`)
			.then(function (response) {
				var respuesta= response.data;
				me.arrayData = respuesta.ninos.data[0];
				me.nombresT = me.arrayData.datos.nombres;
				me.apellidosT = me.arrayData.datos.apellidos;
				me.direccionT = me.arrayData.datos.direccion;
				me.numero_telefono = me.arrayData.datos.numero_telefono;
				me.idT = me.arrayData.datos.id;
				me.codigoT = me.arrayData.codigo;
				me.generoT = me.arrayData.datos.genero;
				me.imagen_perfil_antigua = me.arrayData.ruta_imagen;
				me.fecha_nacimientoT = me.arrayData.datos.fecha_nacimiento;
				me.fecha_ingresoT = me.arrayData.fecha_ingreso;
				me.ocupacionT = me.arrayData.ocupacion;
				me.actividadesT = me.arrayData.actividades;
				me.gradoT = me.arrayData.grado;
				me.sector_idT = me.arrayData.datos.sector_id;
				me.escuela_idT = me.arrayData.escuela_id;
				me.persona_sin_acceso_idT = me.arrayData.datos.persona_sin_acceso_id;
			})
			.catch(function (error) {
				console.log(error);
			});
		},
		async importarSectores(){
			let me = this;
			let encontrado=false;
			let elementoE={}
			const response = await axios.get(
				`/api/sector/get?completo=select`)
			.then(function (response) {
				var respuesta= response.data;
				me.sectoresT = respuesta.sectores.data;
				me.sectoresT.forEach(function(elemento, indice, array) {
					if (elemento.id==me.sector_idT)
					{
						elementoE=elemento
						encontrado=true
					}
				})
				me.sector_idT = encontrado == true ? elementoE : {id:me.sector_idT,nombre:'Sector desactivado'} 
			})
			.catch(function (error) {
				console.log(error);
			});
		},
		async importarEscuelas(){
			let me = this;
			let encontrado=false;
			let elementoE={}
			const response = await axios.get(
				`/api/escuela/get?&completo=select`)
			.then(function (response) {
				var respuesta= response.data;
				me.escuelasT = respuesta.escuelas.data;

				me.escuelasT.forEach(function(elemento, indice, array) {
					if (elemento.id==me.escuela_idT)
					{
						elementoE=elemento
						encontrado=true
					}
				})
				me.escuela_idT = encontrado == true ? elementoE : {id:me.escuela_idT,nombre:'Escuela desactivada'} 
			})
			.catch(function (error) {
				console.log(error);
			});
		},
		validateStep1() {
		return new Promise((resolve, reject) => {
			this.$validator.validateAll('step-1').then(result => {
			if (result) {
				resolve(true)
			} else {
				reject("correct all values");
				this.$vs.notify({
					color:'danger',
					title:'Error en validación!',
					text:'Ingrese todos los campos correctamente'
					});
			}
			})
		})
		},
		validateStep2() {
			return new Promise((resolve, reject) => {
				this.$validator.validateAll("step-2").then(result => {
					if (result) {
						resolve(true)
					} else {
						reject("correct all values");
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
			if (this.ruta_imagen === '' || this.ruta_imagen === undefined){
				this.ruta_imagen= this.imagen_perfil_antigua;
			}
			else{
				this.ruta_imagen= '/storage/public/usuarios/' + this.ruta_imagen;
			}
			axios.put("/api/nino/update/",{
				id:this.id_recibido,
				codigo:this.codigoT,
				nombres:this.nombresT,
				apellidos:this.apellidosT,
				genero:this.generoT,
				ruta_imagen:this.ruta_imagen,
				ocupacion:this.ocupacionT,
				actividades:this.actividadesT,
				grado:this.gradoT,
				fecha_nacimiento:this.getDate(this.fecha_nacimientoT),
				fecha_ingreso:this.getDate(this.fecha_ingresoT),
				direccion:this.direccionT,
				sector_id:this.sector_idT.id,
				escuela_id:this.escuela_idT.id
		}).then(function(response) {
		console.log(response)
			})
			.catch(function(error) {
			console.log(error)
			});
			this.$emit('cerrado','Se cerró el formulario');
			this.$vs.notify({
			color:'success',
			title:`${this.titulo}`,
			text:'La acción se realizo exitósamente'
			});
			this.$router.push('/apadrinamiento/nino');
		},
		vaciar(){
			this.ruta_imagen='';
		},
		editarImagen(){
			this.mostrarEditar=true;
		},
		respuesta(e){
			this.ruta_imagen=e.currentTarget.response.replace(/['"]+/g, '')
			this.head.imagenanterior=this.ruta_imagen
			this.$vs.notify({
					color:'success',
					title:'Imagen subida',
					text:'Acción realizada exitósamente!'
				});
		},
  },
  components: {
    FormWizard,
	TabContent,
	Datepicker,
	vSelect,
  },
	mounted(){
	this.index();
	this.importarSectores();
	this.importarEscuelas();
  },
}
</script>