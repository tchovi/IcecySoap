<template>
  <div>
    <h2>Dashboard</h2>
    <StreamStats />
    <FileManager />
    <PlaylistEditor />
    <Scheduler />
    <button @click="logout">Logout</button>
  </div>
</template>

<script>
import FileManager from './FileManager.vue';
import PlaylistEditor from './PlaylistEditor.vue';
import Scheduler from './Scheduler.vue';
import StreamStats from './StreamStats.vue';

export default {
  components: {
    FileManager,
    PlaylistEditor,
    Scheduler,
    StreamStats
  },
  methods: {
    logout() {
      localStorage.removeItem('token');
      location.reload();
    }
  }
};
</script>