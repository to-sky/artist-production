class HallEvent {
  constructor(rawData) {
    this._rawData = rawData;

    this.init();
  }

  init() {
    this._buildBoundaries();
    this._processPlaces();
    this._processPrices();
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

    this._rawData.places.forEach(p => {
      let color = '#aaa';
      let disabled = true;
      let limit = 0;
      let price = 0;
      p.tickets.forEach(t => {
        if (!t.status) disabled = false;
        limit = t.amount_printed;

        this._rawData.prices.forEach(pr => {
          if (t.price_id === pr.id) {
            color = pr.color;
            price = pr.price;
          }
        });
      });

      switch (p.template) {
        case 'circle':
          this.places.circle.push(Object.assign(p, {
            color,
            disabled,
            limit,
            price
          }));
          break;
        case 'fanzone':
          this.places.fanZone.push(Object.assign(p, {
            color,
            disabled,
            limit,
            price
          }));
          break;
        case 'scene':
          this.places.scene.push(p);
      }

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
  }
}

export default HallEvent;