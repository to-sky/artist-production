import TicketService from '../TicketService/TicketService';

export default class CartCapsule {
  constructor(event) {
    this._event = event;
    this._eventId = event.raw('event.id');
    this._prices = event.prices || [];
    this._priceGroups = event.priceGroups || [];
    this.length = 0;
    this.reservationsList = [];
    this._placeIds = [];

    this._tickets = [];
    this.cart();
  }

  _prepareSelectedPlaces() {
    this._placeIds = [];
    this._foreignPlaceIds = [];
    this._tickets.forEach(t => {
      if (!this._placeIds.includes(t.place_id)) {
        if (t.event_id == this._eventId) {
          this._placeIds.push(t.place_id);
        } else {
          this._foreignPlaceIds.push(t.place_id);
        }
      }
    });

    this._prepareReservationsList();
  }

  _prepareReservationsList() {
    this.reservationsList = [];
    this._tickets.forEach(t => {
      let place = this._getPlace(t.place_id);

      this.reservationsList.push({
        discount_price: t.discount_price || this._getDiscountedPrice(
            this._getPriceById(t.price_id),
            this._getDiscountByPriceGroupId(t.price_group_id)
        ),
        price_group_id: t.price_group_id,
        discount: t.hasOwnProperty('discount_name') ? t.discount_name : this._getDiscountNameByPriceGroupId(t.price_group_id),
        ticket_id: t.id,
        row: t.place_row || (place ? place.row : ''),
        num: t.place_num || (place ? place.num : '')
      });
    });
  }

  _getPlace(place_id) {
    let place = null;

    this._event.raw('places').forEach(p => {
      if (p.id == place_id) place = p;
    });

    return place;
  }

  isReserved(place) {
    let id = place.hasOwnProperty('id') ? place.id : place;

    return this._placeIds.includes(id);
  }

  hasItems() {
    return !!this._tickets.length;
  }

  reserve(place, count, fill) {
    count = count || 1;
    fill = fill || {};

    TicketService.reserve({
      event_id: this._eventId,
      place_id: place.id,
      count: count,
      fill: fill
    }).then(r => {
      r.tickets.forEach(t => {
        this._event.addReserved(t.place_id);
      });

      this.cart();
    });
  }

  free(place, filter) {
    filter = filter || {};

    TicketService.free({
      event_id: this._eventId,
      place_id: place.id,
      filter: filter
    }).then(r => {
      r.tickets.forEach(t => {
        this._event.addAvailable(t.place_id);
      });

      this.cart();
    });
  }

  freeById(ticketId) {
    TicketService.freeById(ticketId)
      .then(r => {
        r.tickets.forEach(t => {
          this._event.addAvailable(t.place_id);
        });

        this.cart();
      })
    ;
  }

  cart() {
    TicketService.card()
      .then(r => {
        this._tickets = r.tickets;
        this.length = this._tickets.length;

        this._prepareSelectedPlaces();

        this._event.selected = this._placeIds;
        this._event.patch([]);
      })
    ;
  }

  total() {
    let total = 0;
    this._tickets.forEach(t => {
      total += t.discount_price;
    });

    return total;
  }

  _getPriceById(id) {
    let price = 0;

    this._prices.forEach(p => {
      if (p.id == id) price = parseFloat(p.price);
    });

    return price;
  }

  _getDiscountByPriceGroupId(id) {
    let discount = 0;

    this._priceGroups.forEach(g => {
      if (g.id == id) discount = parseFloat(g.discount);
    });

    return discount;
  }

  _getDiscountNameByPriceGroupId(id) {
    let name = '';

    this._priceGroups.forEach(g => {
      if (g.id == id) name = g.name;
    });

    return name;
  }

  _getDiscountedPrice(price, discount) {
    let p = (100 - discount) * price / 100;

    return p;
  }
}