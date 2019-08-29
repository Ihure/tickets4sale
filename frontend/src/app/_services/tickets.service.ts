import { Injectable } from '@angular/core';
import { AppSettings } from './app.config';
import { HttpClient } from '@angular/common/http';
import {Observable} from 'rxjs';
import {Tickets} from '../_constants/tickets';

@Injectable()
export class TicketsService {

  constructor(
    private http: HttpClient
  ) { }

  getTicketStatus(sdate, qdate): Observable<Tickets[]> {
    return this.http.post<Tickets[]>(`${AppSettings.API_ENDPOINT}/inventory/checkInventory`, {
      show_date: sdate,
      query_date: qdate,
      web: 1
    });
  }

  getTicket4Sale(sdate, qdate): Observable<Tickets[]> {
    return this.http.post<Tickets[]>(`${AppSettings.API_ENDPOINT}/inventory/getTickets4Sale`, {
      show_date: sdate,
      query_date: qdate
    });
  }

  purchaseTickets(sdate, qdate, id, number): Observable<Tickets[]> {
    return this.http.post<Tickets[]>(`${AppSettings.API_ENDPOINT}/inventory/purchaseTicket`, {
      show_date: sdate,
      query_date: qdate,
      id: id,
      amount: number
    });
  }

}
