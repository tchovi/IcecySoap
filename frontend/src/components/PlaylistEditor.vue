<template>
  <div>
    <h3>Create Playlist</h3>
    <input v-model="name" placeholder="Playlist Name" />
    <input v-model="schedule_time" placeholder="Time (e.g. 14:00)" />
    <button @click="createPlaylist">Create</button>

    <ul>
      <li v-for="p in playlists" :key="p.id">
        {{ p.name }} @ {{ p.schedule_time }}
        <ul>
          <li v-for="t in p.tracks" :key="t.id">{{ t.filename }}</li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return { name: '', schedule_time: '', playlists: [] };
  },
  async mounted() {
    const res = await fetch('/api/playlist', { headers: this.auth() });
    const data = await res.json();
    for (let p of data) {
      const tr = await fetch(`/api/playlist/${p.id}/tracks`, { headers: this.auth() });
      p.tracks = await tr.json();
    }
    this.playlists = data;
  },
  methods: {
    auth() {
      return { Authorization: 'Bearer ' + localStorage.getItem('token') };
    },
    async createPlaylist() {
      await fetch('/api/playlist/create', {
        method: 'POST',
        headers: { ...this.auth(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: this.name, schedule_time: this.schedule_time })
      });
      alert('Created!');
      location.reload();
    }
  }
};
</script>