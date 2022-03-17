function HistoryStack(data, language) {
  this.data = data;
  this.language = language;

  if (this.language === 'en') {
    this.stack = ['Business Units'];
  } else {
    this.stack = ['Unidades de Negócio'];
  }

  this._push = function (el) {
    if (typeof el === 'string') this.stack.push(el);
    return this.stack;
  };

  this._pop = function () {
    if (this.stack.length > 1) return this.stack.pop();
  };

  this._current = function () {
    return this.stack[this.stack.length - 1];
  };

  this._dive = function () {
    let i = 1;
    let currentNode = this.data;
    while (i < this.stack.length) {
      // console.log(`DEPTH = ${i} [${currentNode.name}]`, currentNode)

      if (Array.isArray(currentNode)) {
        currentNode = currentNode.filter((node) => {
          return node.name === this.stack[i];
        })[0];
      } else if (currentNode.children && currentNode.children.length > 0) {
        currentNode =
          currentNode.children.filter((node) => {
            return node.name === this.stack[i];
          })[0] ||
          currentNode.members.filter((node) => {
            return node.name === this.stack[i];
          })[0];
      } else {
        try {
          currentNode = currentNode.members.filter((node) => {
            return node.name === this.stack[i];
          })[0];
        } catch (err) {
          if (err.name === 'TypeError') {
            currentNode = undefined;
          }
        }
      }

      if (!currentNode) {
        throw new Error(`${this.stack[i]} Not Found in Data !`);
      }

      i++;
    }
    return currentNode;
  };

  this.navigate = function (to) {
    if (!to) {
      this.stack = this.stack.slice(0, 1);
      return this.render();
    }

    if (this.stack.indexOf(to) === -1) {
      this._push(to);
    } else {
      if (this.stack.length > 1) {
        this.stack = this.stack.slice(0, this.stack.indexOf(to) + 1);
      }
    }

    return this.render();
  };

  this.breadcrumb = function () {
    const START = '<ul class="breadcrumb">';
    const END = '</ul>';

    let middle = '';
    this.stack.forEach((node, i) => {
      middle = middle.concat(
        `<li class="navigator-link${
          i === this.stack.length - 1 ? ' active' : ''
        }" onClick="router.navigate('${node.trim()}')">${node}</li>`
      );
    });

    return `${START}${middle}${END}`;
  };

  this.headSection = function (data) {
    if (data.type === 'BUSINESS-UNIT' && data.head) {
      return `
                <section id="head" class="panel head" onClick="router.navigate('${data.head.name}')">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-10">
                                <p class='equipe_nome'>${data.head.name.replace(
                                  data.head.nickname,
                                  `<span class='equipe_laranja'>${data.head.nickname}</span>`
                                )}</p>
                                <p class='equipe_email'>${
                                  this.language === 'pt' ? `Responsável ${data.name}` : `Head of ${data.name}`
                                }</p>
                            </div>
                            <div class="col-xs-2">
                                ${
                                  data.head.linkedin
                                    ? `<p class='text-left'><a href='${data.head.linkedin}' target='_blank'><img src='img_site/newLinkedinIcon.png' height="30px"></a></p>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <img src=${
                          data.head.image.substr(0, 9) === 'img_site/'
                            ? encodeURI(data.head.image)
                            : `/img_site/${encodeURI(data.head.image)}`
                        } class="img-responsive hex-image"/>
                    </div>
                </section>
            `;
    } else if (data.type === 'MEMBER') {
      return `
            <section id="head" class="panel head">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-10">
                            <p class='equipe_nome'>${data.name.replace(
                              data.nickname,
                              `<span class='equipe_laranja'>${data.nickname}</span>`
                            )}</p>
                            <p class='equipe_email'>${this.language === 'pt' ? `${data.title}` : `Head of ${data.title}`}</p>
                            ${
                              data.email
                                ? `<p class='equipe_email'><a class='equipe_email' href='mailto:${data.email}'>${data.email}</a></p>`
                                : ''
                            }
                        </div>
                        <div class="col-xs-2">
                            ${
                              data.linkedin
                                ? `<p class='text-left'><a href='${data.linkedin}' target='_blank'><img src='img_site/newLinkedinIcon.png' height="30px"></a></p>`
                                : ''
                            }
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <img src=${
                      data.image.substr(0, 9) === 'img_site/' ? encodeURI(data.image) : `/img_site/${encodeURI(data.image)}`
                    } class="img-responsive hex-image"/>
                </div>
            </section>
            `;
    } else {
      return this.language === 'pt' ? 'NENHUM RESPONSÁVEL ENCONTRADO.' : 'NO HEAD FOUND.';
    }
  };

  this.descriptionSection = function (data) {
    if (data.type === 'BUSINESS-UNIT') {
      return `
                <section id="description" class="panel description">
                    <div class="description-content">
                        <p>${data.description}</p>
                    </div>
                </section>
            `;
    } else if (data.type === 'MEMBER') {
      return `
                <section id="description" class="panel description">
                    <div class="description-content">
                        <p>${data.description}</p>
                    </div>
                </section>
            `;
    } else {
      return this.language === 'pt' ? 'NENHUMA DESCRIÇÃO ENCONTRADA.' : 'NO DESCRIPTION FOUND.';
    }
  };

  this.child = function (child) {
    const shortDescription = child.shortDescription
      ? child.shortDescription
      : this.language === 'pt'
      ? 'NENHUMA DESCRIÇÃO CURTA ENCONTRADA.'
      : 'NO SHORT DESCRIPTION FOUND.';
    return `
            <div class="panel panel-primary child" onClick="router.navigate('${child.name}')">
                <div class="panel-heading text-center">${child.name}</div>
                <div class="panel-body">
                    <p>${shortDescription}</p>    
                </div>
            </div>
        `;
  };

  this.childrenSection = function (data) {
    if (data.type === 'BUSINESS-UNIT' && data.children) {
      let children = [];
      data.children.forEach((child) => children.push(this.child(child)));
      return `
                <section id="children" class="children">
                    ${children.join('')}
                </section>
                
            `;
    } else {
      return '';
    }
  };

  this.member = function (member) {
    return `
            <div class="panel panel-primary member" onClick="router.navigate('${member.name}')">
                <div class="panel-heading text-center">
                    ${member.name}
                </div>
                <div class="panel-body row">
                    <div class="col-sm-4 member-image">
                        <img src=${
                          member.image.substr(0, 9) === 'img_site/'
                            ? encodeURI(member.image)
                            : `/img_site/${encodeURI(member.image)}`
                        } class="img-responsive"/>
                    </div>
                    <div class="col-sm-8 member-content" style="padding-left: 0; margin-left: 0;">
                        <div class="row">
                            <div class="col-xs-12">${member.title ? `<b class='text-center'>${member.title}</b>` : ''}</div>
                        </div>
                        ${member.shortDescription ? `<p>${member.shortDescription}</p>` : ``}
                        ${
                          member.email
                            ? `<p class='equipe_email'><a class='equipe_email' href='mailto:${member.email}'>${member.email}</a></p>`
                            : ''
                        }
                        ${
                          member.linkedin
                            ? `<p class='text-right'><a href='${member.linkedin}' target='_blank'><img src='img_site/newLinkedinIcon.png' height="30px"></a></p>`
                            : ''
                        }
                    </div>
                </div>
            </div>
        `;
  };

  this.membersSection = function (data) {
    if (data.type === 'BUSINESS-UNIT' && data.members) {
      let members = [];
      data.members.forEach((member) => members.push(this.member(member)));
      return `
                <section id="members" class="members">
                    ${members.join('')}
                </section>
                
            `;
    } else {
      return '';
    }
  };

  this.render = function () {
    const data = this._dive();
    let page = '';

    if (this.stack.length === 1) {
      page = `
            <section id="hex" class='hex-container'>
                <svg class="hex hex-nw" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" onClick="router.navigate('${data[0].name}')">
                    <g class="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                            />
                            <svg x="17.5%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                                <image width="100px" height="100px" xlink:href="/img_site/cogs.png"/>
                            </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">${data[0].name}</text>
                    </g>
                </svg>
                
                <svg class="hex hex-ne" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" onClick="router.navigate('${data[1].name}')" >
                    <g class ="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                        />
                        <svg x="20%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                                <image width="100px" height="100px" xlink:href="/img_site/operations.png"/>
                            </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">${data[1].name}</text>
                    </g>
                </svg>
                
                <div class="breakrow"></div>
                
                <svg class="hex hex-w" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" style="cursor: none;" >
                    <g class ="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                        onClick=""/>
                        <svg x="22.5%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                                <image width="100px" height="90px" xlink:href="/img_site/inovations.png"/>
                        </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">Inovação</text>
                    </g>
                </svg>
                
                <svg class="hex hex-c" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" >
                    <g class ="hex-item hex-central-item">
                    
                    </g>
                </svg>
                    <img src='/img_site/admin-navbar-logo.png' style='position: absolute; transform: scale(0.5)'/>

                <svg class="hex hex-e" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" >
                    <g class ="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                            onClick=""/>
                        <svg x="25%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                                <image width="90px" height="90px" xlink:href="/img_site/partner.png"/>
                        </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">Parceria</text>
                    </g>
                </svg>

                <div class="breakrow"></div>

                <svg class="hex hex-sw" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" onClick="router.navigate('${data[2].name}')">
                    <g class ="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                            />
                        <svg x="20%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                            <image width="100px" height="100px" xlink:href="/img_site/service.png"/>
                        </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">${data[2].name}</text>
                    </g>
                </svg>

                <svg class="hex hex-se" version="1.1" xmlns="http://www.w3.org/2000/svg" width="170" height="195" viewbox="0 0 173.20508075688772 200" onClick="router.navigate('${data[3].name}')">
                    <g class ="hex-item">
                        <path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" 
                            />
                        <svg x="20%" y="20%" width="1000px" height="1000px" overflow="visible"> 
                            <image width="125px" height="125px" xlink:href="/img_site/application.png"/>
                        </svg>
                        <text x="50%" y="80%" text-anchor="middle" font-size="20" font-weight="bold" stroke="none" fill="#00426e">${data[3].name}</text>
                    </g>
                </svg>

            </section>
            `;
    } else {
      if (data.type === 'BUSINESS-UNIT') {
        page = `
                <section id="business-unit" class='container-fluid'>    
                    <div class="row">${this.breadcrumb()}</div>
                    <div class="row">
                        ${
                          data.children && data.children.length > 0
                            ? `
                            <div class="col-lg-4 bu-col bu-col-l">
                                ${this.headSection(data)}                                
                                ${this.descriptionSection(data)}
                            </div>

                            <div class="col-lg-4 bu-col bu-col-c">
                                ${this.childrenSection(data)}
                            </div>
                            
                            <div class="col-lg-4 bu-col bu-col-r">
                                ${this.membersSection(data)}
                            </div>
                            `
                            : `
                            <div class="col-lg-8 bu-col bu-col-l">
                                ${this.headSection(data)}
                                ${this.descriptionSection(data)}
                            </div>
                            <div class="col-lg-4 bu-col bu-col-r">
                                ${this.membersSection(data)}
                            </div>
                            `
                        }
                    </div>
                </section>
            
            `;
      } else if (data.type === 'MEMBER') {
        page = `
                <section id="business-unit" class='container-fluid'>    
                    <div class="row">
                        ${this.breadcrumb()}
                    </div>
                    <div class="row">
                        <div class="col-lg-4 bu-col bu-col-l">
                            <div class="row">
                                ${this.headSection(data)}
                            </div>
                        </div>
                        <div class="col-lg-8 bu-col bu-col-c">
                            ${this.descriptionSection(data)}
                        </div>
                    </div>
                </section>
                `;
      }
    }

    document.getElementById('root').innerHTML = page;
  };
}
