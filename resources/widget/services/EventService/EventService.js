import HallEvent from './HallEvent';
import HallSetup from './HallSetup';
import axios from 'axios';

class EventService {
  static load(eventId) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/events/${eventId}`)
        .then(r => {
          resolve(new HallEvent(r.data));
        })
        .catch(e => reject)
      ;
    });
  }

  static loadSetup(eventId) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/events/${eventId}/setup`)
        .then(r => {
          resolve(new HallSetup(r.data));
        })
        .catch(e => reject)
      ;
    });
  }

  static updateTicket(event_id, place, price, amount_printed) {
    return new Promise((resolve, reject) => {
      axios.post(`/api/tickets`, {
        event_id: event_id,
        place_id: place.id,
        price_id: price.id,
        amount_printed: amount_printed || 1
      })
        .then(r => resolve)
        .catch(e => reject)
      ;
    });
  }
}

export default EventService;