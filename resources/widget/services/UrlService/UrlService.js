class UrlService {
  static init() {
    this.dictionary = {};
    this.observers = {};

    UrlService._parse();
    window.addEventListener('hashchange', e => {
      UrlService._parse();
    });
  }

  static _parse() {
    let map = this._getMap();
    let newMap = [];
    let hash = location.hash.replace('#', '');

    let parts = hash.split(',');
    parts.forEach(p => {
      let kv = p.split(':', 2);

      if(kv.length < 2) kv[1] = 1;

      this._set(kv[0], kv[1]);
      newMap.push(kv[0]);
    });

    // Broadcast for deleted properties
    map.filter(e => !newMap.includes(e)).forEach(n => this.broadcast(n, null));
  }

  static _getMap() {
    return this._getNodeMap(this.dictionary, []);
  }

  static _getNodeMap(node, path) {
    let map = [];

    Object.keys(node).forEach(n => {
      if (typeof node[n] === "object") {
        Array.prototype.push.apply(map, this._getNodeMap(node[n], path.concat(n)));
      } else {
        map.push(path.concat(n).join('.'));
      }
    });

    return map;
  }

  static _set(n, v) {
    let head = this.dictionary;
    let path = n.split;

    n.split('.').forEach((k, i) => {
      if (!head.hasOwnProperty(k)) head[k] = (path.length - 1 < i) ? {} : v;
      head = head[k];
    });

    this.broadcast(n, v);
  }

  static get(notation, dVal, callback) {
    dVal = (typeof dVal !== "undefined") ? dVal : null;

    let head = this.dictionary;
    let isSet = true;

    notation.split('.').forEach(k => {
      if (head.hasOwnProperty(k)) {
        head = head[k]
      } else {
        head = {};
        isSet = false;
      }
    });

    if (typeof callback === "function") this.observe(notation, callback);

    return isSet ? head : dVal;
  }

  static observe(notation, callback) {
    this.observers[notation] = this.observers[notation] || [];
    this.observers[notation].push(callback);
  }

  static broadcast(notation, value) {
    this.observers &&
    this.observers[notation] &&
    this.observers[notation].forEach(callback => callback(value));
  }
}

UrlService.init();

export default UrlService;