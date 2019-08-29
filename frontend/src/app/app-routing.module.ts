import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TicketStatusComponent } from './ticket-status/ticket-status.component';
import {PurchaseTicketsComponent} from './purchase-tickets/purchase-tickets.component';

const routes: Routes = [
  { path: '', redirectTo: '/status', pathMatch: 'full' },
  { path: 'status', component: TicketStatusComponent },
  { path: 'purchase', component: PurchaseTicketsComponent },
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
