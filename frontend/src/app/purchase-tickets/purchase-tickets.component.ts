import { Component } from '@angular/core';
import {DatePipe} from '@angular/common';
import {TicketsService} from '../_services';
import {FormControl} from '@angular/forms';
import {Tickets} from '../_constants/tickets';
import {ToastrService} from 'ngx-toastr';

@Component({
  selector: 'app-purchase-tickets',
  templateUrl: './purchase-tickets.component.html',
  styleUrls: ['./purchase-tickets.component.css']
})
export class PurchaseTicketsComponent {

  date = new FormControl(new Date());
  grouped;
  itemsArray: Array<Tickets> = [];

  constructor(
    private datePipe: DatePipe,
    private ticketsService: TicketsService,
    private toastr: ToastrService
  ) { }

  checkStatus(date): void {
    const showDate = this.datePipe.transform(date, 'yyyy-MM-dd');
    const queryDate = this.datePipe.transform(new Date(), 'yyyy-MM-dd');

    this.ticketsService.getTicket4Sale(showDate, queryDate).subscribe(shows => {
    // this.ticketsService.getTicket4Sale('2017-08-15', '2017-08-01').subscribe(shows => {
      this.itemsArray = shows;
      this.grouped = this.itemsArray.reduce(function (r, a) {
        r[a.genre] = r[a.genre] || [];
        r[a.genre].push(a);
        return r;
      }, Object.create(null));

    });
  }

  purchase(ticket: Tickets, date): void {
    // console.log(ticket);
    if ( ticket.amount > ticket.tickets_available) {
      this.toastr.error('You cannot buy more tickets than are available', 'Warning', {
        timeOut: 3000
      });
    } else {
      const showDate = this.datePipe.transform(date, 'yyyy-MM-dd');
      const queryDate = this.datePipe.transform(new Date(), 'yyyy-MM-dd');

      this.ticketsService.purchaseTickets(showDate, queryDate, ticket.id, ticket.amount).subscribe(shows => {
      // this.ticketsService.purchaseTickets('2017-08-15', '2017-08-01', 1, 2).subscribe(shows => {
        this.itemsArray = shows;
        this.grouped = this.itemsArray.reduce(function (r, a) {
          r[a.genre] = r[a.genre] || [];
          r[a.genre].push(a);
          return r;
        }, Object.create(null));
        this.toastr.success('Purchase Successful', 'Success', {
          timeOut: 3000
        });
      });

    }
  }

}
