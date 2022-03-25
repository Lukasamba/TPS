<script setup lang="ts">
import WeekCalendar from "../components/Calendar.vue";
</script>
<template>
  <h1>Hi this is Calendars</h1>
  <WeekCalendar
    :events="[
      {
        startDate: '2022-03-23T09:00:00.000+02:00',
        endDate: '2022-03-23T11:00:00.000+02:00',
        name: 'aaaa',
        place: '14ss',
      },
      {
        startDate: '2022-03-24T10:30:00.000+02:00',
        endDate: '2022-03-24T13:10:00.000+02:00',
        name: 'akds',
        place: '42ss',
      },
    ]"
  />
</template>
