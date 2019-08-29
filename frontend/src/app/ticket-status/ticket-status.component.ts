import { Component } from '@angular/core';
import {FormControl} from '@angular/forms';
import {DatePipe} from '@angular/common';
import {TicketsService} from '../_services';
import {Tickets} from '../_constants/tickets';

@Component({
  selector: 'app-ticket-status',
  templateUrl: './ticket-status.component.html',
  styleUrls: ['./ticket-status.component.css']
})
export class TicketStatusComponent {

  date = new FormControl(new Date());
  grouped;
  itemsArray: Array<Tickets> = [];

  constructor(
    private datePipe: DatePipe,
    private ticketsService: TicketsService,
  ) { }

  checkStatus(date): void {
    const showDate = this.datePipe.transform(date, 'yyyy-MM-dd');
    const queryDate = this.datePipe.transform(new Date(), 'yyyy-MM-dd');

    this.ticketsService.getTicketStatus(showDate, queryDate).subscribe(shows => {
    // this.ticketsService.getTicketStatus('2017-08-15', '2017-08-01').subscribe(shows => {
      this.itemsArray = shows;
      this.grouped = this.itemsArray.reduce(function (r, a) {
        r[a.genre] = r[a.genre] || [];
        r[a.genre].push(a);
        return r;
      }, Object.create(null));

    });
  }

}
