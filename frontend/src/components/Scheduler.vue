<template>
  <div>
    <h3>Stream Control</h3>
    <button @click="start">Start Stream</button>
    <button @click="stop">Stop Stream</button>
  </div>
</template>

<script>
export default {
  methods: {
    auth() {
      return { Authorization: 'Bearer ' + localStorage.getItem('token') };
    },
    async start() {
      await fetch('/api/stream/start', { method: 'POST', headers: this.auth() });
      alert('Stream started');
    },
    async stop() {
      await fetch('/api/stream/stop', { method: 'POST', headers: this.auth() });
      alert('Stream stopped');
    }
  }
};
</script>