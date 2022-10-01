import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';
import flatpickr from 'flatpickr';
import Stream from 'flarum/utils/Stream';
require("flatpickr/dist/flatpickr.css");

const name = Stream('');
const user = Stream('');
const description = Stream('');
const event_start = Stream();
const event_end = Stream();
const mission_id = Stream();

/**
 * THis builds event details based on a FullCalendar concept of object.  CalendarPage talks to api, sends us FC payload
 */
export default class EditEventModal extends Modal {
    oninit(vnode) {
      super.oninit(vnode);

      if (this.attrs.event) {
        const event = this.attrs.event;
        name(event.name());
        description(event.description());
        user(event.user())
        event_start(event.event_start());
        event_end(event.event_end() ? event.event_end() : event.event_start());
        mission_id(event.mission_id())
      }
    }

  /**
   * Builder to create new modal *with empty event* but pre-populated date field.
    * @param startDate
   * @returns {EditEventModal}
   *//*
  withStart(startDate)
  {
    event_start(startDate);
    return this;
  }*/

  title() {
    return name() ? "Edit event details" : "Create new calendar event";
  }

  className() {
    return 'EditEventsModal Modal--small';
  }

  content() {
    return [
      <div className="Modal-body">
        <div className="Form-group">
          <label className="label">Titre</label>
          <input type="text" name="title" className="FormControl" bidi={name} />
        </div>
        <div className="Form-group">
          <label className="label">Mission ID</label>
          <input type="text" name="mission_id" className="FormControl" bidi={mission_id} />
        </div>
        <div className="Form-group">
          <label className="label">Quand</label>
          <div className="PollModal--date" >
            <input id="startpicker" style="opacity: 1; color: inherit" className="FormControl" data-input />
          </div>
        </div>
        <div className="Form-group">
          <label className="label">Détails</label>
          <textarea name="description" className="FormControl" bidi={description} />
          <small>You may use markdown</small>
        </div>
        <div className="Form-group">
          {Button.component({
            type: 'submit',
            className: 'Button Button--primary PollModal-SubmitButton',
            loading: this.loading,
          }, app.translator.trans('flarum-calendar.forum.modal.submit'))}
        </div>
      </div>,
    ];
  }

  configDatePicker(el) {
    flatpickr(el, {
      enableTime: true,
      dateFormat: 'Y-m-d H:i',
      mode: "range",
      defaultDate: [flatpickr.parseDate(event_start(),"Y-m-d h:i K"),flatpickr.parseDate(event_end(),"Y-m-d h:i K")],
      inline: true,
      onChange: dates => {
        event_start(dates[0]);
        event_end(dates[1])
      }
    });
  }

  oncreate(vnode) {
    super.oncreate(vnode);
    console.log("startpicker");
    this.configDatePicker("#startpicker");
  }

  onsubmit(e) {
    e.preventDefault();
    if (!name() || !description() ) {

      app.alerts.show("Events require a name and description");
      return;
    }
    if(!this.attrs.event){
      this.attrs.event = app.store.createRecord('events');
    }
    this.attrs.event.save({
      name: name(),
      description: description(),
      event_start: event_start(),
      event_end: event_end(),
      mission_id: mission_id()
    }).then(this.hide());

  }

}
