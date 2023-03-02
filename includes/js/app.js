$(document).ready(function () {
  let socket = null;
  const connectToSocket = () => {
    let closeSocket = io("http://localhost:5000");
    socket = closeSocket;
    return () => {
      closeSocket?.close();
    };
  };
  connectToSocket();
  let userId = "";
  let userAnswer = {};
  let addAnswer = {};
  let addAnswerOptions = false;
  let addAnswerOptionsArray = [];
  let userAnswers = [];
  let lastPrompt = {};
  let question_id = 0;
  //   var messages = document.getElementById('messages');
  var reply_forms = document.querySelectorAll(".reply_form");
  var add_prompt_forms = document.querySelectorAll(".add_prompt_forms");
  var inputs = document.querySelectorAll(".answer_input");
  var add_prompt_forms_inputs = document.querySelectorAll(
    ".add_prompt_forms_input"
  );
  const genes = document.querySelectorAll(".messages_wrapper");
  const prompts_wrapper = document.querySelectorAll(".prompts_wrapper");
  const maillists = document.querySelectorAll(".maillistmsgs");

  socket.on("me", (id) => {
    //l get my own id then start the process
    userId = id;
    socket.emit("get_question", { question_id, user_id: id });

    reply_forms.forEach((reply_form) => {
      reply_form.addEventListener("submit", function (e) {
        e.preventDefault();
        inputs.forEach((input) => {
          if (input.value) {
            question_id++;
            userAnswer = { user_id: id, answer: input.value, question_id };
            socket.emit("answer_question", userAnswer);
            userAnswers.push({
              prompt: lastPrompt,
              answer: input.value,
              question_id,
            });
            maillists.forEach((maillist, index) => {
              let data_html = `
            <a href="javascript:void(0)" class="mailpreview attachment">
            <div class="imgpic"><i class="-o"></i></div>
            <div class="textmail">
              <strong>${input.value}</strong>
              <p>answer</p>
              <span class="btn btn-default attachdownload"
                ></span>
            </div>
          </a>
            `;
              maillist.innerHTML += data_html;
            }); //set value added by the user also
            input.value = "";
          }
        });
      });
    });

    //start add prompt form
    add_prompt_forms.forEach((form) => {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        add_prompt_forms_inputs.forEach((add_prompt_forms_input) => {
          if (add_prompt_forms_input.value) {
            if (addAnswerOptions) {
              addAnswer = {
                prompt: add_prompt_forms_input.value,
                isList: true,
                options: addAnswerOptionsArray,
                user_id: id,
              };
            } else {
              addAnswer = {
                prompt: add_prompt_forms_input.value,
                isList: false,
                user_id: id,
              };
            }
            socket.emit("add_question", addAnswer);
            //append the value send
            prompts_wrapper.forEach((prompty) => {
              let data_html = `
                  <tr>
                  <td class="primary statusmail">
                    <input type="checkbox" id="check2" class="checkbox" />
                    <label for="check2"></label>
                    <a href="javascript:void(0)">
                      <div class="textmail">
                        <strong>${addAnswer?.prompt}</strong>
                        <!-- <span class="pull-right text-muted"> 
                          12 Jul 16 | 11:00 am
                        </span> -->
                        <p>
                         does have list : ${addAnswer?.isList}
                        </p>
                        <button class="btn btn-danger detele-prompt" data-prompt="${addAnswer?.prompt}">delete</button>
                      </div>
                    </a>
                  </td>
                </tr>`;
              tr.innerHTML += data_html;

              $(".prompts_wrapper").append(tr);
              $(".detele-prompt").on("click", () => {
                removePrompt($(".detele-prompt").attr("data-prompt"));
              });
            });

            add_prompt_forms_input.value = "";
          }
        });
      });
    });

    //add script to add questions
  });
  let tr = document.createElement("tr");
  const removePrompt = (prompt) => {
    socket.emit("remove_question", { prompt, user_id: userId });
  };
  if (userId != null) {
    socket.on("receive_prompts", function (questions) {
      prompts_wrapper.forEach((prompty) => {
        questions.forEach((question) => {
          if (question?.isList) {
            let data_html = `
            <tr>
            <td class="primary statusmail">
              <input type="checkbox" id="check2" class="checkbox" />
              <label for="check2"></label>
              <a href="javascript:void(0)">
                <div class="textmail">
                  <strong>${question?.prompt}</strong>
                  <!-- <span class="pull-right text-muted"> 
                    12 Jul 16 | 11:00 am
                  </span> -->
                  <p>
                   does have list : ${question?.isList}
                  </p>
                  <div class="question_options" data-prompt="${question?.prompt}"></div>
                  <button class="btn btn-danger detele-prompt" data-prompt="${question?.prompt}">delete</button>
                </div>
              </a>
            </td>
          </tr>`;
          tr.innerHTML += data_html;
          }else{
            let data_html = `
            <tr>
            <td class="primary statusmail">
              <input type="checkbox" id="check2" class="checkbox" />
              <label for="check2"></label>
              <a href="javascript:void(0)">
                <div class="textmail">
                  <strong>${question?.prompt}</strong>
                  <!-- <span class="pull-right text-muted"> 
                    12 Jul 16 | 11:00 am
                  </span> -->
                  <p>
                   does have list : ${question?.isList}
                  </p>
                  
                  <button class="btn btn-danger detele-prompt" data-prompt="${question?.prompt}">delete</button>
                </div>
              </a>
            </td>
          </tr>`;
          tr.innerHTML += data_html;
          }
         
          if (question?.isList) {
            question?.options?.forEach((opt) => {
              document
                .querySelectorAll(".question_options")
                .forEach((container) => {
                  container.innerHTML += `
              <p class="btn btn-primary" style="height=10px">${opt.name}</p>

              `;
                });
            });
          }
        });
        if ($(".prompts_wrapper").html() !== tr) {
          $(".prompts_wrapper").html(tr);
        }
        $(".detele-prompt").on("click", () => {
          removePrompt($(".detele-prompt").attr("data-prompt"));
        });
      });
    });
    socket.on("receive_question", function (question) {
      lastPrompt = question?.prompt;

      if (question?.isList) {
        //we loop the options they have and also question on top

        maillists.forEach((maillist, index) => {
          let data_html = `
            <a href="javascript:void(0)" class="mailpreview attachment">
            <div class="imgpic"><i class="icon"></i></div>
            <div class="textmail">
              <strong>${question?.prompt}</strong>
              <p class="question_options ${question?.prompt}"></p>
              <span class="btn btn-default attachdownload"
                ></span>
            </div>
          </a>
            `;
          if (maillist.innerHTML != data_html) {
            maillist.innerHTML += data_html;
            if (question?.isList) {
              question?.options?.forEach((opt) => {
               
                document
                  .querySelectorAll(`.question_options`)
                  .forEach((container) => {
                    container.innerHTML += `
                <p class="btn btn-primary options" style="height=10px">${opt.name}</p>
  
                `;
                  });
              });
            }
          }
        });
        genes.forEach((gene) => {
        
          let data_html = `
             <tr>
             <td class="primary statusmail">
               <input type="checkbox" id="check2" class="checkbox" />
               <label for="check2"></label>
               <a href="javascript:void(0)">
                 <div class="textmail">
                   <strong>${question?.prompt}</strong>
                   <!-- <span class="pull-right text-muted"> 
                     12 Jul 16 | 11:00 am
                   </span> -->
                   <p>
                    does have list : ${question?.isList}
                   </p>
                  
                 </div>
               </a>
             </td>
           </tr>`;

          if (gene.innerHTML != data_html) {
            gene.innerHTML += data_html;
          }
        });
      } else {
        maillists.forEach((maillist, index) => {
          
          let data_html = `
           <a href="javascript:void(0)" class="mailpreview attachment">
           <div class="imgpic"><i class="-o"></i></div>
           <div class="textmail">
             <strong>${question?.prompt}</strong>
             <p>question</p>
             <span class="btn btn-default attachdownload"
               ></span>
           </div>
         </a>
           `;
          maillist.innerHTML += data_html;
         
        });
        genes.forEach((gene) => {
         
          let data_html = `
            <tr>
            <td class="primary statusmail">
              <input type="checkbox" id="check2" class="checkbox" />
              <label for="check2"></label>
              <a href="javascript:void(0)">
                <div class="textmail">
                  <strong>${question?.prompt}</strong>
                  <!-- <span class="pull-right text-muted"> 
                    12 Jul 16 | 11:00 am
                  </span> -->
                  <p>
                   does have list : ${question?.isList}
                  </p>
                </div>
              </a>
            </td>
          </tr>`;
          gene.innerHTML += data_html;
        });
      }

      var item = document.createElement("li");
      item.textContent = question;
      messages.appendChild(item);
      window.scrollTo(0, document.body.scrollHeight);
    });
  }

  return () => {
    socket.off("receive_question");
    socket.off("receive_prompts");
    socket.off("me");
  };
});
