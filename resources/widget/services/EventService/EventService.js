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
        .catch(e => reject(e))
      ;
    });
  }

  static loadSetup(eventId) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/events/${eventId}/setup`)
        .then(r => {
          resolve(new HallSetup(r.data));
        })
        .catch(e => reject(e))
      ;
    });
  }

  static updateTicket(event_id, place, price, count) {
    let placeIds = [];

    if (place.id) {
      placeIds = place.id;
    } else {
      place.forEach(p => {
        placeIds.push(p.id);
      });
    }

    return new Promise((resolve, reject) => {
      axios.post(`/api/tickets`, {
        event_id: event_id,
        place_id: placeIds,
        price_id: price.id,
        count: count || 1
      })
        .then(r => resolve(r))
        .catch(e => reject(e))
      ;
    });
  }
}

export default EventService;