import Ticket from './Ticket';
import ListTicket from './ListTicket';
import TicketTeacher from './TicketTeacher';

export default [
    {path: '/admin/ticket/book', component: Ticket, name: 'ticket.book'},
    {path: '/admin/ticket/tickets', component: ListTicket, name: 'ticket.list'},
    {path: '/admin/ticket/teacher', component: TicketTeacher, name: 'ticket.teacher'},
]