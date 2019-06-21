import TicketService from '../TicketService/TicketService';

export default class CartCapsule {
  constructor(event) {
    this._event = event;
    this._eventId = event.raw('event.id');
    this._tickets = event.getReservedTickets();
    this._prices = event.prices || [];
    this._priceGroups = event.priceGroups || [];
    this.length = this._tickets.length;
    this.reservationsList = [];

    this._prepareSelectedPlaces();
  }

  _prepareSelectedPlaces() {
    this._placeIds = [];
    this._tickets.forEach(t => {
      if (!this._placeIds.includes(t.place_id)) {
        this._placeIds.push(t.place_id);
      }
    });

    this._prepareReservationsList();
  }

  _prepareReservationsList() {
    let places = this._event.raw('places').filter(p => {
      return this._placeIds.includes(p.id);
    });

    this.reservationsList = [];
    places.forEach(p => {
      this._tickets.filter(t => t.place_id == p.id).forEach(t => {
        this.reservationsList.push(Object.assign({}, p, {
          discount_price: this._getDiscountedPrice(
            this._getPriceById(t.price_id),
            this._getDiscountByPriceGroupId(t.price_group_id)
          ),
          price_group_id: t.price_group_id,
          discount: this._getDiscountNameByPriceGroupId(t.price_group_id),
          ticket_id: t.id
        }));
      });
    });
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
      this._tickets.push(...r.tickets);
      this.length = this._tickets.length;

      this._event._rawData.selectedTickets = this._tickets;
      this._event.patch(r.tickets);
      this._prepareSelectedPlaces();
    });
  }

  free(place, filter) {
    filter = filter || {};

    TicketService.free({
      event_id: this._eventId,
      place_id: place.id,
      filter: filter
    }).then(r => {
      this._tickets = this._tickets.filter(t => {
        let keep = true;

        r.tickets && r.tickets.forEach(rt => {
          if (rt.id == t.id) keep = false;
        });

        return keep;
      });
      this._event._rawData.selectedTickets = this._tickets;
      this._event.patch(r.tickets);
      this.length = this._tickets.length;

      this._prepareSelectedPlaces();
    });
  }

  freeById(ticketId) {
    TicketService.freeById(ticketId)
      .then(r => {
        this._tickets = this._tickets.filter(t => {
          let keep = true;

          r.tickets && r.tickets.forEach(rt => {
            if (rt.id == t.id) keep = false;
          });

          return keep;
        });
        this._event._rawData.selectedTickets = this._tickets;
        this._event.patch(r.tickets);
        this.length = this._tickets.length;

        this._prepareSelectedPlaces();
      })
    ;
  }

  total() {
    let total = 0;
    this._tickets.forEach(t => {
      total += this._getDiscountedPrice(
        this._getPriceById(t.price_id),
        this._getDiscountByPriceGroupId(t.price_group_id)
      );
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