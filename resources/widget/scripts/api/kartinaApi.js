import axios from "axios";
import { KASSIR_URL } from "../../global.config";
import jsonp from "jsonp";
import store from "../../store/store.js";
import swal from "vue-sweetalert2";

const __uid = "pWjdLQzoGIPiA33exD9tnw";

function getAuthToken() {
  return axios
    .get(KASSIR_URL + "LoginCommand.cmd", {
      params: {
        __uid: __uid
      }
    })
    .then(response => {
      return response.data.__auth;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function updateFlashEventDataCommand(token, event, ts) {
  return axios
    .get(KASSIR_URL + "UpdateFlashEventDataCommand.cmd", {
      params: {
        format: "json",
        __auth: token,
        event: event,
        ts: ts
      }
    })
    .then(response => {
      return response.data;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function serializeUri(param) {
  let str = [];
  for (let p in param)
    if (param.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(param[p]));
    }
  return str.join("&");
}

function loadHall(token, event) {
  return new Promise((resolve, reject) => {
    let param = {
      __auth: token,
      event: event
    };
    let query = serializeUri(param);
    jsonp(
      KASSIR_URL + "GetFlashHallDataCommand.cmd" + "?" + query,
      (err, data) => {
        if (err) {
          return reject(err.message);
        }
        return resolve(formationHalles(data));
      }
    );
  });
}

function formationHalles(response) {
  let zones = response.zones;
  let places = response.places;
  let labels = response.labels;
  zones["labels"] = labels;

  for (let i = 0; i < zones.length; i++) {
    zones[i].places = [];
  }

  // add width/height and text to zones
  for (let i = 0; i < labels.length; i++) {
    for (let j = 0; j < zones.length; j++) {
      if (labels[i].text.toLowerCase() === zones[j].name.toLowerCase()) {
        zones[j]["description"] = {
          text: labels[i].text.toLowerCase(),
          rotation: labels[i].rotation
        };
      }
    }
  }

  // find scene
  let scenes = [];
  for (let i = 0; i < places.length; i++) {
    if (places[i].template === "scene") {
      scenes.push(places[i]);
    }
  }
  store.commit("setScene", scenes);

  // accommodation places to zones
  for (let i = 0; i < places.length; i++) {
    for (let j = 0; j < zones.length; j++) {
      if (zones[j].id === places[i].zone) {
        zones[j].places.push(places[i]);
      }
    }
  }

  // computed area of zones
  let minWidthContainer = Infinity,
    minHeightContainer = Infinity;
  let maxWidthContainer = 0,
    maxHeightContainer = 0;
  for (let i = 0; i < zones.length; i++) {
    let minWidth = Infinity,
      minHeight = Infinity;
    let maxWidth = 0,
      maxHeight = 0;
    let places = zones[i].places;
    for (let j = 0; j < places.length; j++) {
      // for zones
      let x = Math.round(places[j].x),
          y = Math.round(places[j].y);
      if (minHeight > y) minHeight = y;
      if (maxHeight < y) maxHeight = y;
      if (minWidth > x) minWidth = x;
      if (maxWidth < x) maxWidth = x;

      // for container
      if (minHeightContainer > y) minHeightContainer = y;
      if (maxHeightContainer < y) maxHeightContainer = y;
      if (minWidthContainer > x) minWidthContainer = x;
      if (maxWidthContainer < x) maxWidthContainer = x;
    }
    zones[i]["area"] = {
      left: minWidth,
      top: minHeight,
      width: maxWidth - minWidth + 30, // 30 is size of last button with absolute position
      height: maxHeight - minHeight + 30
    };
  }
  store.commit("setZoneContainer", {
    width: maxWidthContainer - minWidthContainer + 30,
    height: maxHeightContainer - minHeightContainer + 30
  });

  // computed position of places
  for (let i = 0; i < zones.length; i++) {
    let places = zones[i].places;
    let zonePosition = {
      x: zones[i]["area"].left,
      y: zones[i]["area"].top
    };
    for (let j = 0; j < places.length; j++) {
      if (places[j].template === "fanzone") continue;
      places[j].x = places[j].x - zonePosition.x;
      places[j].y = places[j].y - zonePosition.y;
    }
  }

  return zones;
}

function getEventStatus(token, event) {
  return axios
    .get(KASSIR_URL + "GetEventStatusCommand.cmd", {
      params: {
        __auth: token,
        event: event
      }
    })
    .then(response => {
      return response.data;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function getOrderCommand(token) {
  return axios
    .get(KASSIR_URL + "GetOrderCommand.cmd", {
      params: {
        __auth: token
      }
    })
    .then(response => {
      return response.data;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function changePlaceCommand(token, event, pid) {
  return axios
    .get(KASSIR_URL + "ChangePlaceCommand.cmd", {
      params: {
        __auth: token,
        event,
        pid,
        returnStatus: 1
      }
    })
    .then(response => {
      return response.data;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function deletePlaceFromCartCommand(token, pid) {
  return axios
    .get(KASSIR_URL + "DeletePlaceFromCartCommand.cmd", {
      params: {
        __auth: token,
        orderItem: pid
      }
    })
    .then(response => {
      return response.data;
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

function getPartnerEvents(token, event) {
  return axios
    .get(KASSIR_URL + "GetPartnerEventsCommand.cmd", {
      params: {
        __auth: token,
        eventId: event,
        SkipStatistic: 1,
        SkipDescription: 1
      }
    })
    .then(response => {
      return response.data.Events[0];
    })
    .catch(error => {
      let errors = [];
      let response = {};

      try {
        response = JSON.parse(error.request.response);

        if (response.errors) {
          for (let prop in response.errors) {
            errors.push(response.errors[prop]);
          }

          return swal("Warning!", errors.join(" "), "warning");
        }

        swal("Error!", response.message, "error");
      } catch (e) {
        swal({
          title: "Error!",
          text: error.request.status + " " + error.request.statusText,
          type: "error"
        });
      }
    });
}

export {
  getAuthToken,
  loadHall,
  getPartnerEvents,
  getEventStatus,
  updateFlashEventDataCommand,
  getOrderCommand,
  changePlaceCommand,
  deletePlaceFromCartCommand
};
