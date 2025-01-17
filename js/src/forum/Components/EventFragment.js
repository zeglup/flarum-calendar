import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import username from 'flarum/helpers/username';
import EditEventModal from "./EditEventModal";
import fullTime from 'flarum/helpers/fullTime';
import dayjs from 'dayjs';

export default class EventFragment extends Component {

  oninit(vnode) {
    super.oninit(vnode);
  }

  title() {
    return this.attrs.event.name();
  }

  className() {
    return 'EventTeaser Modal--small';
  }

  view() {
    require('dayjs/locale/fr')
    dayjs.locale('fr')
    var localizedFormat = require('dayjs/plugin/localizedFormat')
    dayjs.extend(localizedFormat)
      return <div>
        <p>
          <b>Début</b> : <span style="text-transform: capitalize;">{ dayjs(this.attrs.event.event_start()).format('LLLL') }</span> <br/>
        </p>
        <p id="eventdescription"/>

        Créé par <span style="text-transform: capitalize;"><a href={app.route.user(this.attrs.event.user())} config={m.route}>
          {username(this.attrs.event.user())}
        </a></span>
        {(app.session.user && (app.session.user.canModerateEvents || this.attrs.event.user.id === app.session.user.id)) ?
          (<div>
              {Button.component({
                icon: 'fas fa-edit',
                onclick: this.editLaunch.bind(this),
                className: 'Button Button--icon Button--link',
              })},
              {Button.component({
                icon: 'fas fa-trash-alt',
                onclick: this.deleteEvent.bind(this),
                className: 'Button Button--icon Button--link',
              })}
            </div>
          ) : ''
        }
      </div>
  }

  oncreate(vnode) {
    const descElement = document.getElementById("eventdescription");
    s9e.TextFormatter.preview(this.attrs.event.description(), descElement);
  }

  editLaunch() {
    console.log({"message": "[webbinaro/flarum-calendar] edit event ", "attrs": this.attrs.event})
    app.modal.show(
      EditEventModal, {"event": this.attrs.event}
    );
  }

  deleteEvent() {
    console.log({"message": "[webbinaro/flarum-calendar] delete event ", "event": this.attrs.event})
    const events = this.attrs.events;
    this.attrs.event.delete().then(()=>{
      app.alerts.show("Event Deleted");
      m.route(app.route('advevents'));
      //app.history.back();
    });
  }
}
