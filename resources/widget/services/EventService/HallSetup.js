import HallEvent from "./HallEvent";

class HallSetup extends HallEvent {
  _processPlaces() {
    this.places = {
      circle: [],
      scene: [],
      fanZone: []
    };

    this._rawData.places.forEach(p => {
      let color = '#aaa';

      this._rawData.tickets.forEach(t => {
        if (t.place_id !== p.id) return;

        this._rawData.prices.forEach(pr => {
          if (t.price_id === pr.id) color = pr.color;
        });
      });

      switch (p.template) {
        case 'circle':
          this.places.circle.push(Object.assign(p, {
            color: color
          }));
          break;
        case 'fanzone':
          this.places.fanZone.push(Object.assign(p, {
            color: color
          }));
          break;
        case 'scene':
          this.places.scene.push(p);
      }

    });

    if (!this.places.scene.length) this._computeScene();
  }
}

export default HallSetup;