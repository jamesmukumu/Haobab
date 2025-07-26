import { Component } from '@angular/core';
import { ConfirmationService, MessageService } from 'primeng/api';
import { Application } from '../../../services/application';
@Component({
  selector: 'app-application-form',
  standalone: false,
  templateUrl: './application-form.html',
  styleUrl: './application-form.css',
  providers:[ConfirmationService,MessageService]
})
export class ApplicationForm {

  loading = false
  onUpload(event:any) {

    for(let file of event.files) {
        this.uploadedFiles.push(file);
    }

}
  uploadedFiles: any[] = [];
  passportPhoto:any

selectPassport(event:any){
  console.log(event)
this.passportPhoto = event.currentFiles[0]
}

towns:any[] = [
{
"name":"Nakuru City",
"value":"Nakuru City"
},
{
  "name":"Nairobi City",
  "value":"Nairobi City"
  },
  {
    "name":"Mombasa City",
    "value":"Mombasa City"
    },

]

formData = {
  fullNames: '',
  email: '',
  phoneNumber: '',

  briefIntro: '',
  DOB:"",
  salaryExpectations:""
};
townChoosen:any
workExperiences = [
  {
    company: '',
    position: '',
    duration: '',
    description: ''
  }
];

addWorkExperience() {
  this.workExperiences.push({
    company: '',
    position: '',
    duration: '',
    description: ''
  });
}


removeWorkExperience(index: number) {
  if (this.workExperiences.length > 1) {
    this.workExperiences.splice(index, 1);
  }
}
constructor(private confirmService:ConfirmationService,private msg:MessageService,private application:Application){}
onSubmit(event: any) {

  const form = event.target;
  Object.keys(form).forEach(field => {
    if (form[field]?.control) {
      form[field].control.markAsTouched();
    }
  });

  if (!this.isFormValid()) {
    return this.msg.add({severity:"error",detail:"Ensure all field are filled",sticky:true})
  }

  this.confirmService.confirm({
    target: event.currentTarget as EventTarget,
    message: 'Are you sure you want to Submit this application?',
    icon: 'pi pi-exclamation-triangle',
    rejectButtonProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptButtonProps: {
      label: 'Save'
    },
    accept: () => {
     this.loading = true
  
      let payload = {
      "DOB":new Date(this.formData.DOB).toLocaleDateString(),
      "intro":this.formData.briefIntro,
      "fullNames":this.formData.fullNames,
      "emailAddress":this.formData.email,
      "location":this.townChoosen,
      "salaryExpectations":this.formData.salaryExpectations,
      "applicationDocs":this.uploadedFiles,
      "workExperiences":this.workExperiences,
      "phoneNumber":this.formData.phoneNumber,
      "passportPhoto":this.passportPhoto
      }
   
      this.application.saveApplication(payload).then((data:any)=>{
      if(data.message === "Application Received"){
      this.loading = false
      this.msg.add({severity:"success",detail:"Application Processed",sticky:true})
      }else{
        this.loading = false
        this.msg.add({severity:"error",detail:data.error,sticky:true})
      }
      }).catch((err)=>console.error(err))
   

    },
    reject: () => {
      this.resetForm()
    }
  });
}


isFormValid(): boolean {
  return this.formData.fullNames !== '' &&
         this.formData.email !== '' &&
         this.formData.phoneNumber !== '' &&
     
         this.workExperiences.every(exp => 
           exp.company !== '' &&
           exp.position !== '' &&
           exp.duration !== ''
         );
}

resetForm() {
  this.formData = {
    fullNames: '',
    email: '',
    phoneNumber: '',
   
    briefIntro: '',
    DOB:"",
    salaryExpectations:""
  };
  this.workExperiences = [{
    company: '',
    position: '',
    duration: '',
    description: ''
  }];
}
}
