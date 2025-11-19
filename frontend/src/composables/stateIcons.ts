// import L from 'leaflet';
import icon_I_feel_lonely from '/icons/icon_I_feel_lonely.png';
import icon_I_need_to_chat from '/icons/icon_I_need_to_chat.png';
import icon_Lets_get_to_know_each_other from '/icons/icon_Lets_get_to_know_each_other.png';
import icon_Buy_me_a_coffee from '/icons/icon_Buy_me_a_coffee.png';
import icon_Surprise_me from '/icons/icon_Surprise_me.png';
import icon_Looking_for_something from '/icons/icon_Looking_for_something.png';
import icon_Looking_for_something_more from '/icons/icon_Looking_for_something_more.png';
import icon_Shall_we_take_a_room from '/icons/icon_Shall_we_take_a_room.png';
import icon_bw_I_feel_lonely from '/icons/icon_bw_I_feel_lonely.png';
import icon_bw_I_need_to_chat from '/icons/icon_bw_I_need_to_chat.png';
import icon_bw_Lets_get_to_know_each_other from '/icons/icon_bw_Lets_get_to_know_each_other.png';
import icon_bw_Buy_me_a_coffee from '/icons/icon_bw_Buy_me_a_coffee.png';
import icon_bw_Surprise_me from '/icons/icon_bw_Surprise_me.png';
import icon_bw_Looking_for_something from '/icons/icon_bw_Looking_for_something.png';
import icon_bw_Looking_for_something_more from '/icons/icon_bw_Looking_for_something_more.png';
import icon_bw_Shall_we_take_a_room from '/icons/icon_bw_Shall_we_take_a_room.png';
import {Icon} from 'leaflet'

const stateIcons: Record<string, Icon> = {
  'I feel lonely': new Icon({
    iconUrl: icon_I_feel_lonely,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'I need to chat': new Icon({
    iconUrl: icon_I_need_to_chat,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Let\'s get to know each other': new Icon({
    iconUrl: icon_Lets_get_to_know_each_other,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Buy me a coffee!': new Icon({
    iconUrl: icon_Buy_me_a_coffee,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Surprise me!': new Icon({
    iconUrl: icon_Surprise_me,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Looking for something': new Icon({
    iconUrl: icon_Looking_for_something,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Looking for something more': new Icon({
    iconUrl: icon_Looking_for_something_more,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Shall we take a room?': new Icon({
    iconUrl: icon_Shall_we_take_a_room,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
};

const stateBwIcons: Record<string, Icon> = {
  'I feel lonely': new Icon({
    iconUrl: icon_bw_I_feel_lonely,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'I need to chat': new Icon({
    iconUrl: icon_bw_I_need_to_chat,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Let\'s get to know each other': new Icon({
    iconUrl: icon_bw_Lets_get_to_know_each_other,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Buy me a coffee!': new Icon({
    iconUrl: icon_bw_Buy_me_a_coffee,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Surprise me!': new Icon({
    iconUrl: icon_bw_Surprise_me,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Looking for something': new Icon({
    iconUrl: icon_bw_Looking_for_something,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Looking for something more': new Icon({
    iconUrl: icon_bw_Looking_for_something_more,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
  'Shall we take a room?': new Icon({
    iconUrl: icon_bw_Shall_we_take_a_room,
    iconSize: [50, 82],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  }),
};

export { stateIcons, stateBwIcons };