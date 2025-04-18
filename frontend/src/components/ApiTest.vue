<script>
export default {
  data: () => ({
    apiResponse: null,
    weatherLogs: []
  }),

  mounted() {
    window.Echo.channel('weather-updates')
      .listen('.weather.updated', (payload) => {
        // 'payload' contains whatever you broadcast from Laravel
        // Example: { userId: 1, weatherLog: { ... } }

        // To update local state, you can manipulate weatherLogs
        // e.g. find the matching user’s log and update it
        console.log('xxxxxx', payload);
        this.updateWeather(payload);
      });
  },

  created() {
    this.fetchData()
  },

  methods: {
    async fetchData() {
      const url = 'http://localhost:8000/'
      this.apiResponse = await (await fetch(url)).json()
    },
    updateWeather(payload) {
      // Logic to update weatherLogs in real-time
      // Example:
      console.log("yyyyyyyyyyyy", payload);
      let idx = this.weatherLogs.findIndex(
        w => w.userId === payload.userId
      );
      if (idx !== -1) {
        this.weatherLogs[idx] = payload.weatherLog;
      }
    }
  }
}
</script>

<template>
  <div v-if="!apiResponse">
    Pinging the api...
  </div>

  <div v-if="apiResponse">
    The api responded with: <br />
    <code>
    {{ apiResponse }}
    </code>
  </div>
</template>