import { Injectable } from '@angular/core';
import axios from "axios"
@Injectable({
  providedIn: 'root'
})
// 
export class Application {
baseUrl = "https://haobab-master-s9axmi.laravel.cloud/api"
async saveApplication(Payload: any) {
  try {
    let formData = new FormData();


    const appender = (key: string, value: any) => {
      if (key && value !== undefined && value !== null) {
        formData.append(key, value);
      }
    };

  
    appender("fullNames", Payload.fullNames);
    appender("emailAddress", Payload.emailAddress);
    appender("phoneNumber", Payload.phoneNumber);
    appender("location", Payload.location);
    appender("salaryExpectations", Payload.salaryExpectations);
    appender("DOB", Payload.DOB);
    appender("intro", Payload.intro);

   
    appender("workExperiences", JSON.stringify(Payload.workExperiences));

    
    if (Payload.applicationDocs && Array.isArray(Payload.applicationDocs)) {
      Payload.applicationDocs.forEach((file: File, index: number) => {
        formData.append(`applicationDocs[${index}]`, file);
      });
    }

    let response = await axios.post(`${this.baseUrl}/save/application`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return response.data;
  } catch (error) {
    console.error(error);
    return error;
  }
}

}
