<template>
  <div>
    <h3>Upload Music</h3>
    <input type="file" @change="uploadFile" />
    <ul><li v-for="f in files" :key="f">{{ f }}</li></ul>
  </div>
</template>

<script>
export default {
  data() {
    return { files: [] };
  },
  async mounted() {
    const res = await fetch('/api/files', { headers: this.auth() });
    this.files = await res.json();
  },
  methods: {
    auth() {
      return { Authorization: 'Bearer ' + localStorage.getItem('token') };
    },
    async uploadFile(e) {
      const formData = new FormData();
      formData.append('file', e.target.files[0]);
      await fetch('/api/files/upload', {
        method: 'POST',
        headers: this.auth(),
        body: formData
      });
      alert('Uploaded!');
      location.reload();
    }
  }
};
</script>