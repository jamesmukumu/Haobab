import { NgModule, provideBrowserGlobalErrorListeners, provideZonelessChangeDetection } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import {TextareaModule} from "primeng/textarea"
import { AppRoutingModule } from './app-routing-module';
import { ConfirmPopupModule } from 'primeng/confirmpopup';
import { App } from './app';
import { Header } from './components/header/header';
import { Home } from './pages/home/home';
import { ApplicationForm } from './components/forms/application-form/application-form';
import { SelectModule } from 'primeng/select';
import { InputTextModule } from 'primeng/inputtext';
import {FormsModule} from "@angular/forms"
import { DatePicker } from 'primeng/datepicker';
import { HttpClientModule } from '@angular/common/http';
import { FileUpload } from 'primeng/fileupload';
import { ToastModule } from 'primeng/toast';
import { Footer } from './components/footer/footer';
import { Commoners } from './components/commoners/commoners';
@NgModule({
  declarations: [
    App,
    Header,
    Home,
    ApplicationForm,
    Footer,
    Commoners
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule,
    ToastModule,
    FileUpload,
    SelectModule,
    TextareaModule,
    ConfirmPopupModule,
    InputTextModule,
    DatePicker,
    FormsModule
  ],
  providers: [
    provideBrowserGlobalErrorListeners(),
    provideZonelessChangeDetection()
  ],
  bootstrap: [App]
})
export class AppModule { }
