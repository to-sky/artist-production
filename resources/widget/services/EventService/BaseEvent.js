class BaseEvent {
  constructor(rawData) {
    this._rawData = rawData;
    this.selected = [];

    this.init();
  }

  init() {
    this._buildBoundaries();
    this._processPrices();
    this._processPlaces();
    this._processLabels();
  }

  getPreselected() {
    return this.preselected || [];
  }

  raw(notation) {
    let parts = notation.split('.');

    let head = this._rawData;
    try {
      parts.forEach(p => {
        head = head[p];
      });
    }
    catch (e) {
      return '';
    }

    return head;
  }

  _getPlace(place_id) {
    let place = null;

    this.raw('places').forEach(p => {
      if (p.id == place_id) place = p;
    });

    return place;
  }

  addAvailable(place_id) {

    let place = this._getPlace(place_id);

    if (place.reserved > 0) {
      place.reserved--;
      place.available++;
    }
  }

  addReserved(place_id) {
    let place = this._getPlace(place_id);

    if (place.available > 0) {
      place.reserved++;
      place.available--;
    }
  }

  patch(updates) {
    updates && updates.forEach(p => {
      this._patchPlace(p);
    });

    this._processPlaces();
  }

  _patchPlace(place) {
    this._rawData.places = this._rawData.places.map(p => {
      if (p.id === place.id) return place;
      return p;
    });
  }

  _buildBoundaries() {
    let box = [0, 0, 0, 0];

    this._rawData.places.forEach((p, i) => {
      let dx = p.x - p.width/2;
      let dy = p.y - p.height/2;

      if (!i) {
        box = [
          dx,
          dy,
          dx + p.width,
          dy + p.height
        ];
      } else {
        box = [
          Math.min(box[0], dx),
          Math.min(box[1], dy),
          Math.max(box[2], dx + p.width),
          Math.max(box[3], dy + p.height)
        ];
      }
    });

    box[1] -= 50;
    box[3] += 50;
    this.viewBox = box;
    this.viewBoxString = box.join(' ');
  }

  _processPlaces() {
    this.places = {
      circle: [],
      scene: [],
      fanZone: []
    };

    this.fontSize = 30;
    
    this.preselected = [];

    this._rawData.places.forEach(p => {
      let color = '#aaa';
      let disabled = p.available <= 0;
      let reserved = !disabled && (p.reserved > 0);
      let inSelected = this.selected.includes(p.id);
      let limit = disabled ? 0 : p.available;
      let price = 0;

      if (disabled) {
        if (reserved) color = '#f00';
        if (inSelected) disabled = false;
      } else {
        this._rawData.prices.forEach(pr => {
          if ((p.price_id === pr.id) && !disabled) {
            color = pr.color;
            price = pr.price;
          }
        });
      }

      this.fontSize = Math.min(this.fontSize, parseInt(p.width/2));

      let head = null;
      let place = null;
      switch (p.template) {
        default:
          head = this.places.circle;
          place = Object.assign(p, {
            color,
            disabled,
            limit,
            price
          });
          break;
        case 'fanzone':
          head = this.places.fanZone;
          place = Object.assign(p, {
            color,
            disabled,
            limit,
            price
          });
          break;
        case 'scene':
          head = this.places.scene;
          place = p;
          break;
      }

      head.push(place);
      if (inSelected) this.preselected.push(place);
    });

    if (!this.places.scene.length) this._computeScene();
  }

  _computeScene() {
    let width = this.viewBox[2] - this.viewBox[0];
    let height = 70;
    let x = this.viewBox[0] - width/2;
    let y = this.viewBox[1] - height - 30;

    this.places.scene.push({
      height: height,
      template: "scene",
      text:"СЦЕНА   STAGE   BÜHNE",
      width: width,
      x: x,
      y: y
    });
  }

  _processPrices() {
    this.prices = this._rawData.prices;

    this.showPrices = [];
  }

  shouldShowPrice(id) {
    return !this.showPrices.length || this.showPrices.includes(id);
  }

  getShowPricePos(id) {
    return this.showPrices.indexOf(id);
  }

  addShowPrice(id) {
    let pos = this.getShowPricePos(id);

    if (pos === -1) {
      this.showPrices.push(id);
    }
  }

  removeShowPrice(id) {
    let pos = this.getShowPricePos(id);

    if (pos === -1) return;

    this.showPrices.splice(pos, 1);
  }

  _processLabels()
  {
    this.labels = this._rawData.labels;
  }
}

export default BaseEvent;