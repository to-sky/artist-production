import axios from "axios/index";

export default class TicketService {
  static manage(event_id, place, price, count) {
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

  static reserve(data) {
    data.count = data.count || 1;

    return new Promise((resolve, reject) => {
      axios.post('/api/tickets/reserve', data)
        .then(r => resolve(r.data))
        .catch(e => reject(e))
      ;
    });
  }

  static free(data) {
    return new Promise((resolve, reject) => {
      axios.post('/api/tickets/free', data)
        .then(r => resolve(r.data))
        .catch(e => reject(e))
      ;
    });
  }

  static freeById(id) {
    return new Promise((resolve, reject) => {
      axios.post(`/api/tickets/free/${id}`)
        .then(r => resolve(r.data))
        .catch(e => reject(e))
      ;
    });
  }
}