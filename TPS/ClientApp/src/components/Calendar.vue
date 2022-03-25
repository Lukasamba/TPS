<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { DateTime, Settings } from "luxon";
import { Interface } from "readline";

Settings.defaultLocale = "lt";
interface Event {
  endDate: string;
  startDate: string;
  name: string;
  place: string;
}
interface ProcessedEvent {
  startDate: DateTime;
  endDate: DateTime;
  name: string;
  place: string;
}
interface WeekDayEvent extends ProcessedEvent {
  style: object;
}
function processWeekDayEvents(
  day: DateTime,
  events: ProcessedEvent[]
): WeekDayEvent[] {
  const thisDayEvents = events.filter(
    (e) => e.startDate.hasSame(day, "day") || e.endDate.hasSame(day, "day")
  );
  return thisDayEvents.map((e) => ({
    ...e,
    style: {
      "grid-row": `${e.startDate.hour + 2} / ${e.endDate.hour + 2}`,
      "margin-top": `${e.startDate.minute}px`,
      "margin-bottom": `-${e.endDate.minute}px`,
    },
  }));
}

const { events, currentDate } =
  defineProps<{ events: Event[]; currentDate?: string }>();

const processedEvents = computed<ProcessedEvent[]>(() =>
  events.map((e) => ({
    ...e,
    startDate: DateTime.fromISO(e.startDate),
    endDate: DateTime.fromISO(e.endDate),
  }))
);
const currentWeekDate = computed(() =>
  currentDate
    ? DateTime.fromISO(currentDate).startOf("week")
    : DateTime.now().startOf("week")
);

const weekOffSet = ref(0);

const shownNextWeek = computed(() =>
  currentWeekDate.value.plus({ weeks: weekOffSet.value }).startOf("week")
);

const currentWeekDays = computed(() =>
  Array(7)
    .fill(null)
    .map((_, days) => shownNextWeek.value.plus({ days }))
);
const currentDayHours = Array(24)
  .fill(null)
  .map((_, i) => ({ "grid-row": `${i + 2}` }));

const weekDayEvents = computed(() =>
  currentWeekDays.value.map((day) =>
    processWeekDayEvents(day, processedEvents.value)
  )
);
</script>
<template>
  <div class="weekcalendarpos">
    <div class="buttons">
      <button @click="weekOffSet -= 1">&lt</button>
      <button @click="weekOffSet = 0">Šiandiena</button>
      <button @click="weekOffSet += 1">&gt</button>
    </div>
    <div class="weekcalendar">
      <div class="weekday">
        <div class="header"></div>
        <div v-for="(style, i) in currentDayHours" class="hour" :style="style">
          {{ i }}:00
        </div>
      </div>
      <div v-for="(day, i) in currentWeekDays" :key="i" class="weekday">
        <div class="header">
          <span class="Month">{{ day?.toFormat("LLLL") }}</span>
          <span class="MonthDay">{{ day?.toFormat("d") }}</span>
          <span class="WeekDay">{{ day?.toFormat("cccc") }}</span>
        </div>
        <div v-for="style in currentDayHours" class="hour" :style="style"></div>
        <div
          v-for="event in weekDayEvents[i]"
          class="event"
          :style="event.style"
        >
          {{ event.name }}
          {{ event.place }}
        </div>
      </div>
    </div>
  </div>
  <span>{{ DateTime.now().toISO() }}</span>
</template>
<style scoped lang="scss">
.weekcalendarpos {
  padding: 20px;
}

/* CSS */
button {
  background: linear-gradient(to bottom right, #ef4765, #ff9a5a);
  border: 0;
  border-radius: 12px;
  color: #ffffff;
  cursor: pointer;
  font-family: -apple-system, system-ui, "Segoe UI", Roboto, Helvetica, Arial,
    sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 2;
  outline: transparent;
  padding: 0 0.7rem;
  //text-align: center;
  text-decoration: none;
  transition: box-shadow 0.2s ease-in-out;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  white-space: nowrap;
  margin-left: 5px;
  font-size: 16px;
}

button:not([disabled]):focus {
  box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.5),
    -0.125rem -0.125rem 1rem rgba(239, 71, 101, 0.5),
    0.125rem 0.125rem 1rem rgba(255, 154, 90, 0.5);
}

button:not([disabled]):hover {
  box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.5),
    -0.125rem -0.125rem 1rem rgba(239, 71, 101, 0.5),
    0.125rem 0.125rem 1rem rgba(255, 154, 90, 0.5);
}
.buttons {
  display: flex;
  justify-content: right;
  margin-bottom: 10px;
}
.weekcalendar {
  border: 1px solid black;
  display: grid;
  grid-template-columns: auto repeat(7, 1fr);
  & > .weekday {
    border: 1px solid lightgray;
    display: grid;
    grid-template-rows: 6em;
    grid-auto-rows: 60px;
    grid-template-columns: repeat(3, 1fr);
    background: #eee;
    text-align: center;
    & > .header {
      text-align: left;
      grid-column: 1 / -1;
      border: 1px solid lightgray;
      padding-left: 5px;
      & > span {
        display: block;
        padding: 0;
        margin: 0;
      }
      .MonthDay {
        font-weight: bold;
        font-size: 30px;
      }
      .WeekDay {
        font-size: 20px;
        text-transform: uppercase;
      }
      .Month {
        font-size: 12px;
        text-transform: uppercase;
      }
    }
    & > .hour {
      border: 1px solid lightgray;
      grid-column: 1/ -1;
    }
    & > .event {
      background-color: rgb(144, 238, 144);
      grid-column: 1/ -1;
    }
  }
}
</style>
