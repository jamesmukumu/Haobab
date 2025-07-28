import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { BrowserAnimationsModule, provideAnimations } from '@angular/platform-browser/animations';
import { providePrimeNG } from 'primeng/config';
import Material from '@primeng/themes/material';
import { Home } from './pages/home/home';
const routes: Routes = [
  {path:"",component:Home}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
  providers: [
    provideAnimations(),
    providePrimeNG({
      theme: {
        preset:Material
      }
    })
  ]
})
export class AppRoutingModule { }
