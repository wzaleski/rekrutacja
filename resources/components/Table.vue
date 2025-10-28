<template>
  <div>
    <VDataTable
        :headers="headers"
        :items="vehicles"
        :items-per-page="10"
        class="elevation-1"
    >
      <template v-slot:top>
        <VToolbar flat>
          <VToolbarTitle>Vehicles</VToolbarTitle>
          <VSpacer></VSpacer>
          <VBtn color="primary" dark class="mb-2" @click="openDialog(null)">
            New Vehicle
          </VBtn>
        </VToolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <VIcon small class="mr-2" @click="openDialog(item)">
          mdi-pencil
        </VIcon>
        <VIcon small @click="deleteItem(item)">
          mdi-delete
        </VIcon>
      </template>
      <template v-slot:item.createdAt="{ item }">
        {{ formatDate(item.createdAt) }}
      </template>
      <template v-slot:item.updatedAt="{ item }">
        {{ formatDate(item.updatedAt) }}
      </template>
    </VDataTable>

    <VDialog v-model="dialog" max-width="500px">
      <VCard>
        <VCardTitle>
          <span class="text-h5">{{ formTitle }}</span>
        </VCardTitle>
        <VCardText>
          <VContainer>
            <VRow>
              <VCol cols="12">
                <VTextField
                    v-model="editedItem.registrationNumber"
                    label="Registration Number"
                ></VTextField>
              </VCol>
              <VCol cols="12" sm="6">
                <VTextField v-model="editedItem.brand" label="Brand"></VTextField>
              </VCol>
              <VCol cols="12" sm="6">
                <VTextField v-model="editedItem.model" label="Model"></VTextField>
              </VCol>
              <VCol cols="12" sm="6">
                <VSelect
                    v-model="editedItem.type"
                    :items="['Passenger', 'Bus', 'Truck']"
                    label="Type"
                ></VSelect>
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardActions>
          <VSpacer></VSpacer>
          <VBtn color="blue darken-1" text @click="close">Cancel</VBtn>
          <VBtn color="blue darken-1" text @click="save">Save</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

const headers = [
  {title: 'No.', key: 'id', sortable: false},
  {title: 'Registration Number', key: 'registrationNumber'},
  {title: 'Brand', key: 'brand'},
  {title: 'Model', key: 'model'},
  {title: 'Vehicle Type', key: 'type'},
  {title: 'Creation Date', key: 'createdAt'},
  {title: 'Modification Date', key: 'updatedAt'},
  {title: 'Actions', key: 'actions', sortable: false}
]

const vehicles = ref([])
const dialog = ref(false)
const editedIndex = ref(-1)
const editedItem = ref({
  id: null,
  registrationNumber: '',
  brand: '',
  model: '',
  type: '',
  createdAt: null,
  updatedAt: null
})

const formTitle = ref('New Vehicle')

onMounted(async () => {
  await fetchVehicles()
})

async function fetchVehicles() {
  try {
    const response = await axios.get('/vehicles/list')
    vehicles.value = response.data.results
  } catch (error) {
    console.error('Error fetching vehicles:', error)
  }
}

async function openDialog(item) {
  if (item) {
    try {
      const response = await axios.get(`/vehicles/${item.id}`);
      editedItem.value = {...response.data[0]};
    } catch (error) {
      console.error('Error fetching vehicle:', error);
      editedItem.value = {...item};
    }
    formTitle.value = 'Edit Vehicle';
  } else {
    editedIndex.value = -1
    editedItem.value = {
      id: null,
      registrationNumber: '',
      brand: '',
      model: '',
      type: '',
      createdAt: null,
      updatedAt: null
    }
    formTitle.value = 'New Vehicle'
  }
  dialog.value = true
}

function close() {
  dialog.value = false
}

async function save() {
  editedItem.value.registrationNumber = editedItem.value.registrationNumber.toUpperCase()

  try {
    if (editedIndex.value > -1) {
      await axios.put(`/vehicles/${editedItem.value.id}`, editedItem.value)
      Object.assign(vehicles.value[editedIndex.value], editedItem.value)
    } else {
      const response = await axios.post('/vehicles', editedItem.value)
      vehicles.value.push(response.data[0])
    }
    close()
  } catch (error) {
    console.log(error);
    const errorMessage = error.response.data.error || 'An error occurred while saving the vehicle';
    alert(`Error saving vehicle: ${errorMessage}`);
  }
}

async function deleteItem(item) {
  if (confirm('Are you sure you want to delete this vehicle?')) {
    try {
      await axios.delete(`/vehicles/${item.id}`)
      vehicles.value.splice(vehicles.value.indexOf(item), 1)
    } catch (error) {
      console.error('Error deleting vehicle:', error)
    }
  }
}

function formatDate(timestamp) {
  const date = new Date(timestamp * 1000)
  return date.toISOString().slice(0, 19).replace('T', ' ')
}
</script>
