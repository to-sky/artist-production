import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    zones: "",
    prices: "",
    eventName: "",
    eventImg: "",
    hallName: "",
    cityName: "",
    endDate: "",
    endTime: "",
    selectedItems: [],
    checkoutPopup: false,
    zoneContainer: [],
    arrayColors: [],
    checkedColors: {},
    renderActiveZone: false,
    scene: {},
    globalLocale: "de",
    buildingAddress: "",
    places: [],
    fanzoneItems: []
  },
  mutations: {
    setEventName(state, name) {
      state.eventName = name;
    },
    setEventImg(state, img) {
      state.eventImg = img;
    },
    setHallName(state, name) {
      state.hallName = name;
    },
    setCityName(state, name) {
      state.cityName = name;
    },
    setEndDate(state, date) {
      state.endDate = date;
    },
    setEndTime(state, time) {
      state.endTime = time;
    },
    addSelectedItems(state, item) {
      if (item.hasOwnProperty("isFanzone")) {
        let newObject = Object.assign({}, item);
        let id = 0;
        state.fanzoneItems.forEach(fanzoneItem => {
          id = Math.max(id, fanzoneItem.fanzoneID);
        });
        newObject["fanzoneID"] = id + 1;
        state.fanzoneItems.push(newObject);
        state.selectedItems.push(newObject);
      } else {
        state.selectedItems.push(item);
      }
    },
    removeSelectedItems(state, item) {
      for (let i = 0; i < state.selectedItems.length; i++) {
        if (state.selectedItems[i].hasOwnProperty("isFanzone")) {
          if (item.fanzoneID === state.selectedItems[i].fanzoneID) {
            state.selectedItems.splice(i, 1);
          }
        } else {
          if (state.selectedItems[i].id === item.id)
            state.selectedItems.splice(i, 1);
        }
      }
    },
    setCheckoutPopup(state, bool) {
      state.checkoutPopup = bool;
    },
    setZoneContainer(state, container) {
      state.zoneContainer = container;
    },
    setArrayColors(state, color) {
      state.arrayColors.push(color);
    },
    changeCheckedColors(state, color) {
      if (state.checkedColors[color]) {
        delete state.checkedColors[color];
      } else {
        state.checkedColors[color] = true;
      }
    },
    setScene(state, scene) {
      state.scene = scene;
    },
    setGlobalLocale(state, lang) {
      state.globalLocale = lang;
    },
    setBuildingAddress(state, address) {
      state.buildingAddress = address;
    },
    setPlaces(state, places) {
      state.places = places;
    }
  },
  getters: {
    getEventName(state) {
      return state.eventName;
    },
    getEventImg(state) {
      return state.eventImg;
    },
    getHallName(state) {
      return state.hallName;
    },
    getCityName(state) {
      return state.cityName;
    },
    getEndDate(state) {
      return state.endDate;
    },
    getEndTime(state) {
      return state.endTime;
    },
    getSelectedItems(state) {
      return state.selectedItems;
    },
    getCheckoutPopup(state) {
      return state.checkoutPopup;
    },
    getZoneContainer(state) {
      return state.zoneContainer;
    },
    getArrayColors(state) {
      return state.arrayColors;
    },
    getCheckedColors(state) {
      return state.checkedColors;
    },
    getRenderActiveZone(state) {
      return state.renderActiveZone;
    },
    getScene(state) {
      return state.scene;
    },
    getGlobalLocale(state) {
      return state.globalLocale;
    },
    getBuildingAddress(state) {
      return state.buildingAddress;
    },
    getPlaces(state) {
      return state.places;
    }
  },
  actions: {}
});
