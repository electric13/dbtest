import axios from "axios";

async function fetchMaterials() {
    try {
	    const url = "/api/materials";
        const response = await axios.get(url);
        const results = response.data;
    } catch (err) {
        if (err.response) {
          // client received an error response (5xx, 4xx)
          console.log("Server Error:", err)
        } else if (err.request) {
          // client never received a response, or request never left
          console.log("Network Error:", err)
        } else {
          console.log("Client Error:", err)
        }
    }
}
