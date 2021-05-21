import BaseEvent from "./BaseEvent";
import CartCapsule from "./CartCapsule";

class HallEvent extends BaseEvent {
  init() {
    this._buildBoundaries();
    this._processPrices();
    this._processPlaces();
    this._processLabels();
    this._processPriceGroups();
    this._prepareCart();
  }

  _processPriceGroups() {
    this.priceGroups = this._rawData.priceGroups;
  }

  _prepareCart() {
    this.cart = new CartCapsule(this);
  }

  hasManyPriceGroups() {
    return !!this.priceGroups.length;
  }

  getSelectedPlaces() {
    // todo: make method to return tickets grouped by place and price group
  }
}

export default HallEvent;