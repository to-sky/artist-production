import HallEvent from './HallEvent';
import HallSetup from './HallSetup';
import axios from 'axios';

class EventService {
  static load(eventId) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/events/${eventId}`)
        .then(r => {
          resolve(new HallEvent(r.data));
          this.lastDeltaTime = r.data.ts;
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

  static loadDelta(eventId) {
    !this.lastDeltaTime && (this.lastDeltaTime = 0);

    return new Promise((resolve, reject) => {
      axios.get(`/api/events/${eventId}/delta?last_update=${escape(this.lastDeltaTime)}`)
        .then(r => {
          this.lastDeltaTime = r.data.ts;
          resolve(r.data.updates);
        })
        .catch(e => reject(e))
      ;
    });
  }
}

export default EventService;